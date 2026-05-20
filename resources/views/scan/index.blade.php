<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>QR Scanner Barang</title>

<script src="https://unpkg.com/html5-qrcode"></script>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', sans-serif;
    background: linear-gradient(135deg, #667eea, #764ba2);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* CARD */
.container {
    width: 100%;
    max-width: 420px;
    background: white;
    border-radius: 20px;
    padding: 20px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
}

/* TITLE */
h2 {
    text-align: center;
    margin-bottom: 15px;
    color: #333;
}

/* SELECT */
select {
    width: 100%;
    padding: 12px;
    border-radius: 10px;
    border: 1px solid #ddd;
    margin-bottom: 10px;
    font-size: 14px;
}

/* SCANNER BOX */
#reader {
    width: 100%;
    border-radius: 12px;
    overflow: hidden;
    margin-bottom: 10px;
}

/* BUTTON */
.btn {
    width: 100%;
    padding: 12px;
    border: none;
    border-radius: 12px;
    font-size: 14px;
    cursor: pointer;
    margin-top: 8px;
    transition: 0.3s;
}

.btn-start {
    background: #28a745;
    color: white;
}

.btn-stop {
    background: #dc3545;
    color: white;
}

.btn:hover {
    opacity: 0.85;
}

/* RESULT */
.result {
    margin-top: 15px;
    padding: 12px;
    border-radius: 12px;
    background: #f8f9fa;
    font-size: 14px;
    color: #333;
}

.label {
    font-weight: bold;
    color: #555;
}

/* MOBILE FIX */
@media(max-width: 480px){
    .container {
        margin: 10px;
        padding: 15px;
    }
}
</style>
</head>

<body>

<div class="container">
    <h2>📦 Scan QR Barang</h2>

    <select id="cameraSelect"></select>

    <div id="reader"></div>

    <button class="btn btn-start" onclick="startScanner()">▶ Start Scan</button>
    <button class="btn btn-stop" onclick="stopScanner()">⛔ Stop</button>

    <div class="result" id="result">
        Belum ada scan
    </div>
</div>

<script>
let html5QrCode;
let cameras = [];

async function loadCameras() {
    cameras = await Html5Qrcode.getCameras();
    let select = document.getElementById('cameraSelect');

    cameras.forEach(cam => {
        let option = document.createElement('option');
        option.value = cam.id;
        option.text = cam.label || 'Kamera';
        select.appendChild(option);
    });
}

async function startScanner() {
    const cameraId = document.getElementById('cameraSelect').value;

    html5QrCode = new Html5Qrcode("reader");

    await html5QrCode.start(
        cameraId,
        {
            fps: 15,
            qrbox: { width: 250, height: 250 }
        },
        qrCodeMessage => {
            handleResult(qrCodeMessage);
            stopScanner();
        }
    );
}

function stopScanner() {
    if (html5QrCode) {
        html5QrCode.stop().catch(err => console.log(err));
    }
}

function handleResult(data) {
    try {
        let json = JSON.parse(data);

        document.getElementById("result").innerHTML = `
            <div><span class="label">Nama:</span> ${json.nama}</div>
            <div><span class="label">Type ID:</span> ${json.type_id}</div>
            <div><span class="label">Unit ID:</span> ${json.unit_id}</div>
            <div><span class="label">Brand:</span> ${json.brand}</div>
            <div><span class="label">Jumlah:</span> ${json.jumlah}</div>
            <div><span class="label">Harga:</span> ${json.harga}</div>
            <div><span class="label">Tanggal:</span> ${json.tanggal_beli}</div>
            <div><span class="label">Ruang:</span> ${json.ruang}</div>
            <div><span class="label">Sumber:</span> ${json.sumber}</div>
        `;

        sendToServer(json);

    } catch (e) {
        document.getElementById("result").innerHTML = "❌ QR tidak valid";
    }
}

function sendToServer(data) {
    fetch('/api/scan-barang', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(res => {
        if (res.status === 'success') {
            alert("✅ Data tersimpan");
        } else {
            alert("❌ " + res.message);
        }
    })
    .catch(err => {
        alert("❌ Server tidak merespon");
        console.log(err);
    });
}

loadCameras();
</script>

</body>
</html>