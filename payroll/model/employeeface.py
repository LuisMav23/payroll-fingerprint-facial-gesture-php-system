from flask import Flask, render_template, Response, jsonify, request
import cv2
import time

app = Flask(__name__)


@app.route("/")
def home():
    return render_template("home.html")


@app.route("/start_recognition")
def start_recognition():
    return render_template("start_recognized.html")


def generate_frames():
    cap = cv2.VideoCapture(0)
    cap.set(3, 640)  # Width
    cap.set(4, 480)  # Height

    while True:
        success, frame = cap.read()
        if not success:
            break

        # Add your face recognition logic here
        recognized_faces = []  # This should hold recognized face data

        # Resize the frame to create a zoom effect
        zoom_factor = 1.2
        frame_height, frame_width = frame.shape[:2]
        new_width = int(frame_width * zoom_factor)
        new_height = int(frame_height * zoom_factor)
        webcam_resized = cv2.resize(frame, (new_width, new_height))
        start_x = (new_width - frame_width) // 2
        start_y = (new_height - frame_height) // 2
        webcam_cropped = webcam_resized[
            start_y : start_y + frame_height, start_x : start_x + frame_width
        ]

        ret, buffer = cv2.imencode(".jpg", webcam_cropped)
        frame = buffer.tobytes()

        yield (b"--frame\r\n" b"Content-Type: image/jpeg\r\n\r\n" + frame + b"\r\n\r\n")


@app.route("/video_feed")
def video_feed():
    return Response(
        generate_frames(), mimetype="multipart/x-mixed-replace; boundary=frame"
    )


@app.route("/loadData")
def load_data():
    data = []
    return jsonify(data)


if __name__ == "__main__":
    app.run(debug=True)
