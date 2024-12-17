import subprocess
from flask import Flask, jsonify, render_template, request, redirect, url_for
import os
import mysql.connector
import base64
from PIL import Image
from io import BytesIO
import os
import base64
import pickle
import numpy as np
import pandas as pd
from io import BytesIO
from flask import Flask, request, jsonify
from PIL import Image
import face_recognition_api
import json
import requests
from flask import Flask, request, jsonify
import mysql.connector
from datetime import datetime
import time
from flask import Flask, request, jsonify
import mysql.connector
from datetime import datetime

app = Flask(__name__)


config = {
    "user": "root",
    "password": "",
    "host": "localhost",
    "database": "payroll_mdb",
    "raise_on_warnings": True,
}


@app.route("/")
def home():
    return render_template("home.html")


@app.route("/gesture")
def gesture():
    # Get email from query parameter
    email = request.args.get("email")

    try:
        conn = mysql.connector.connect(**config)
        cursor = conn.cursor(dictionary=True)  # For better column name access
        query = "SELECT * FROM wy_employees WHERE emp_code = %s"
        cursor.execute(query, (email,))  # Execute query with parameterized input
        employee = cursor.fetchone()  # Fetch the first result

        cursor.close()
        conn.close()

        if employee:
            return render_template("gesture.html", employee=employee)
        else:
            return "Employee not found", 404

    except mysql.connector.Error as err:
        return f"Error: {err}", 500


@app.route("/start_recognition")
def detection():
    try:

        conn = mysql.connector.connect(**config)

        cursor = conn.cursor()

        query = "SELECT emp_code,first_name,last_name FROM wy_employees"

        cursor.execute(query)

        result = cursor.fetchall()
    except mysql.connector.Error as err:
        result = []
        print(f"Error: {err}")

    finally:
        cursor.close()
        conn.close()
    print(result)

    return render_template("login.html", administrator=result)


from datetime import datetime

last_request_time = 0

from datetime import datetime, timedelta


@app.route("/gesture-check", methods=["POST"])
def check_gesture():
    data = request.get_json()
    emp_code = data.get("empcode")
    gesture = data.get("gesture")
    print(gesture)
    if not emp_code or not gesture:
        return jsonify({"error": "Missing required fields"}), 400

    gesture_map = {"checkin": "time-in", "overtime": "overtime", "checkout": "time-out"}

    action_name = gesture_map.get(gesture)
    if not action_name:
        return jsonify({"error": "Invalid gesture"}), 400

    try:
        conn = mysql.connector.connect(**config)
        cursor = conn.cursor()

        today_date = datetime.now().date()
        current_time = datetime.now().time()

        check_query = """
            SELECT attendance_id, action_name FROM wy_attendance
            WHERE emp_code = %s AND attendance_date = %s
            ORDER BY action_time DESC LIMIT 1
        """
        cursor.execute(check_query, (emp_code, today_date))
        attendance_record = cursor.fetchone()

        if not attendance_record:

            if action_name != "time-in":
                return jsonify({"error": "You must time in first"}), 400

            insert_query = """
                INSERT INTO wy_attendance (emp_code, attendance_date, action_name, action_time, emp_desc)
                VALUES (%s, %s, %s, %s, %s)
            """
            cursor.execute(
                insert_query, (emp_code, today_date, "time-in", current_time, gesture)
            )
            conn.commit()
            return jsonify({"message": "Time-in recorded successfully"}), 200

        last_action = attendance_record[1]

        if last_action == "time-in" and action_name == "time-in":
            return jsonify({"error": "You already timed in today"}), 400

        if action_name == "time-out":

            check_timeout_query = """
                SELECT attendance_id FROM wy_attendance
                WHERE emp_code = %s AND attendance_date = %s AND action_name = %s
            """
            cursor.execute(check_timeout_query, (emp_code, today_date, "time-out"))
            timeout_exists = cursor.fetchone()

            if timeout_exists:
                return jsonify({"error": "You cannot clock in again"}), 400

            insert_query = """
                INSERT INTO wy_attendance (emp_code, attendance_date, action_name, action_time, emp_desc)
                VALUES (%s, %s, %s, %s, %s)
            """
            cursor.execute(
                insert_query, (emp_code, today_date, "time-out", current_time, gesture)
            )
            conn.commit()
            return jsonify({"message": "Time-out recorded successfully"}), 200

        if action_name == "overtime":
            if last_action == "time-in":

                insert_query = """
                    INSERT INTO wy_attendance (emp_code, attendance_date, action_name, action_time, emp_desc)
                    VALUES (%s, %s, %s, %s, %s)
                """
                cursor.execute(
                    insert_query,
                    (emp_code, today_date, "time-out", current_time, gesture),
                )
                conn.commit()

            update_query = """
                UPDATE wy_attendance
                SET action_name = %s, action_time = %s, emp_desc = %s
                WHERE attendance_id = %s AND action_name != 'time-in'
            """
            cursor.execute(
                update_query,
                ("overtime", current_time, gesture, attendance_record[0]),
            )
            conn.commit()
            return jsonify({"message": "Overtime recorded successfully"}), 200

        return jsonify({"error": "Invalid action sequence"}), 400

    except mysql.connector.Error as err:
        return jsonify({"error": str(err)}), 500

    finally:
        if conn.is_connected():
            cursor.close()
            conn.close()


@app.route("/train", methods=["POST"])
def train():
    emp_code = request.form.get("emp_code")

    if not emp_code:
        return jsonify({"success": False, "message": "No emp_code provided"}), 400

    current_dir = os.path.dirname(os.path.abspath(__file__))
    student_folder = os.path.join(current_dir, "training-images", emp_code)

    if not os.path.exists(student_folder):
        os.makedirs(student_folder)

    images = request.files.getlist("images")
    for image in images:
        filename = image.filename
        image.save(os.path.join(student_folder, filename))

    try:
        subprocess.run(
            ["python", os.path.join(current_dir, "create_encodings.py")], check=True
        )
    except subprocess.CalledProcessError:
        return (
            jsonify({"success": False, "message": "Failed to create encodings."}),
            500,
        )

    try:
        subprocess.run(["python", os.path.join(current_dir, "train.py")], check=True)
    except subprocess.CalledProcessError:
        return jsonify({"success": False, "message": "Failed to train model."}), 500

    return redirect(url_for("detection"))


def get_prediction_images(prediction_dir):
    files = [x[2] for x in os.walk(prediction_dir)][0]
    l = []
    exts = [".jpg", ".jpeg", ".png"]
    for file in files:
        _, ext = os.path.splitext(file)
        if ext.lower() in exts:
            l.append(os.path.join(prediction_dir, file))
    return l


@app.route("/capture", methods=["POST"])
def capture():
    image_data = request.form.get("image")
    if image_data:
        image_data = image_data.split(",")[1]
        image = Image.open(BytesIO(base64.b64decode(image_data)))
        image_path = "captured_image.jpg"
        image.save(image_path)

        fname = "classifier.pkl"
        encoding_file_path = "encoded-images-data.csv"

        df = pd.read_csv(encoding_file_path)
        full_data = np.array(df.astype(float).values.tolist())

        X = np.array(full_data[:, 1:-1])
        y = np.array(full_data[:, -1:])

        if os.path.isfile(fname):
            with open(fname, "rb") as f:
                le, clf = pickle.load(f)
        else:
            return jsonify({"status": "error", "message": "Classifier does not exist."})

        img = face_recognition_api.load_image_file(image_path)
        X_faces_loc = face_recognition_api.face_locations(img)
        faces_encodings = face_recognition_api.face_encodings(
            img, known_face_locations=X_faces_loc
        )

        closest_distances = clf.kneighbors(faces_encodings, n_neighbors=1)
        is_recognized = [
            closest_distances[0][i][0] <= 0.5 for i in range(len(X_faces_loc))
        ]

        predictions = [
            (
                (le.inverse_transform([int(pred)])[0].title(), loc)
                if rec
                else ("Unknown", loc)
            )
            for pred, loc, rec in zip(
                clf.predict(faces_encodings), X_faces_loc, is_recognized
            )
        ]

        return jsonify(
            {
                "status": "success",
                "message": "Image received, processed, and predictions made.",
                "predictions": predictions,
            }
        )

    return jsonify({"status": "error", "message": "No image received."})


if __name__ == "__main__":
    app.run(debug=True, host="0.0.0.0")
