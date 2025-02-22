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
from datetime import datetime, date

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

@app.route("/apply-overtime", methods=["POST"])
def apply_overtime():
    data = request.get_json()
    emp_code = data.get("emp_code")

    if not emp_code:
        return jsonify({"error": "Employee code is required"}), 400

    try:

        conn = mysql.connector.connect(**config)
        cursor = conn.cursor(dictionary=True)

        today_date = datetime.now().date()
        current_time = datetime.now().time()

        # Check if the employee has timed out
        check_query = """
            SELECT * FROM wy_attendance
            WHERE emp_code = %s AND attendance_date = %s
            ORDER BY action_time DESC LIMIT 1
        """
        cursor.execute(check_query, (emp_code, today_date))
        attendance_record = cursor.fetchone()
        
        if not attendance_record or attendance_record["action_name"] != 'time-out':
            return jsonify({"error": "You must time out first before applying for overtime"}), 400

        # Insert overtime record into wy_attendance
        insert_attendance_query = """
            INSERT INTO wy_attendance (emp_code, attendance_date, action_name, action_time, emp_desc)
            VALUES (%s, %s, %s, %s, %s)
        """
        cursor.execute(insert_attendance_query, (emp_code, today_date, "overtime in", current_time, "Overtime in"))

        # Insert overtime record into wy_overtimes with status
        # insert_overtime_query = """
        #     INSERT INTO wy_overtimes (emp_code, overtime_out_time, overtime_date, status)
        #     VALUES (%s, %s, %s, %s)
        # """
        # cursor.execute(insert_overtime_query, (emp_code, attendance_record['action_time'], today_date, "pending"))
        conn.commit()

        return jsonify({"message": "Overtime application submitted successfully, pending approval"})

    except mysql.connector.Error as err:
        return jsonify({"error": f"Database error: {err}"}), 500
    finally:
        cursor.close()
        conn.close()


@app.route("/gesture")
def gesture():
    # Get email from query parameter
    email = request.args.get("email")

    try:
        # Connect to the database
        conn = mysql.connector.connect(**config)
        cursor = conn.cursor(dictionary=True)  # For better column name access

        # Query employee data
        employee_query = "SELECT * FROM wy_employees WHERE emp_code = %s"
        cursor.execute(employee_query, (email,))
        employee = cursor.fetchone()  # Fetch the first result

        if not employee:
            cursor.close()
            conn.close()
            return "Employee not found", 404

        # Check if the employee has already timed in today
        today_date = date.today().strftime("%Y-%m-%d")  # Get today's date in YYYY-MM-DD format
        attendance_query = """
            SELECT * FROM wy_attendance
            WHERE emp_code = %s AND attendance_date = %s
            ORDER BY action_time DESC LIMIT 1
        """
        cursor.execute(attendance_query, (email, today_date))
        attendance_info = cursor.fetchone()
        attendance_status = ''  # Fetch the result
        if not attendance_info:
            attendance_status = "Not timed in"
        elif attendance_info["action_name"] == "time-in":
            attendance_status = "Timed in"
        elif attendance_info["action_name"] == "time-out":
            attendance_status = "Timed out"
        elif attendance_info["action_name"] == "overtime in":
            attendance_status = "Overtime in"
        else:
            attendance_status = "Overtime out"

        cursor.close()
        conn.close()
        return render_template("gesture.html", employee=employee, attendance_status=attendance_status)

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
from flask_cors import CORS


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

        late_time_threshold = datetime.strptime("07:15:00", "%H:%M:%S").time()

        check_query = """
            SELECT attendance_id, action_name, action_time FROM wy_attendance
            WHERE emp_code = %s AND attendance_date = %s
            ORDER BY action_time DESC LIMIT 1
        """
        cursor.execute(check_query, (emp_code, today_date))
        attendance_record = cursor.fetchone()

        if not attendance_record:
            if action_name != "time-in":
                return jsonify({"error": "You must time in first"}), 400

            is_late = current_time > late_time_threshold
            emp_desc = "Late" if is_late else "On Time"

            insert_query = """
                INSERT INTO wy_attendance (emp_code, attendance_date, action_name, action_time, emp_desc)
                VALUES (%s, %s, %s, %s, %s)
            """
            cursor.execute(
                insert_query, (emp_code, today_date, "time-in", current_time, emp_desc)
            )
            conn.commit()
            return jsonify({"message": "Time-in recorded successfully", "status": emp_desc}), 200

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
            if last_action != "overtime in":
                return jsonify({"error": "You must apply for overtime first before overtime out"}), 400

            check_out_query = """
                SELECT action_time FROM wy_attendance
                WHERE emp_code = %s AND attendance_date = %s AND action_name = %s
                ORDER BY action_time DESC LIMIT 1
            """
            cursor.execute(check_out_query, (emp_code, today_date, "overtime in"))
            overtime_out_record = cursor.fetchone()

            if not overtime_out_record:
                return jsonify({"error": "You must apply for overtime before recording overtime out"}), 400

            check_out_time = overtime_out_record[0]
            check_out_time = (datetime.min + check_out_time).time() if isinstance(check_out_time, timedelta) else check_out_time

            overtime_duration = datetime.combine(today_date, current_time) - datetime.combine(today_date, check_out_time)
            overtime_hours = round(overtime_duration.total_seconds() / 3600)

# Ensure it's not negative
            if overtime_hours < 0:
                return jsonify({"error": "Invalid overtime hours calculated"}), 400
            print("OVERTIME HOURS: ", overtime_hours)

            insert_query = """
                INSERT INTO wy_attendance (emp_code, attendance_date, action_name, action_time, emp_desc)
                VALUES (%s, %s, %s, %s, %s)
            """
            cursor.execute(
                insert_query, (emp_code, today_date, "overtime", current_time, gesture)
            )
            conn.commit()

            insert_overtime_query = """
                INSERT INTO wy_overtimes (emp_code, overtime_hours, overtime_date, status)
                VALUES (%s, %s, %s, %s)
            """
            cursor.execute(insert_overtime_query, (emp_code, overtime_hours, today_date, "pending"))
            conn.commit()

            return jsonify({"message": f"Overtime recorded successfully: {overtime_hours} hours"}), 200

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

CORS(app)

if __name__ == "__main__":
    app.run(debug=True, host="localhost")