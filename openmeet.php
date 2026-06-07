<?php
// OpenMeet - FIXED Video Call with Working Video
// Save as index.php

$roomsDir = __DIR__ . '/rooms';
if (!file_exists($roomsDir)) mkdir($roomsDir, 0777, true);

// Clean old rooms
foreach (glob("$roomsDir/*.json") as $file) {
    if (filemtime($file) < time() - 3600) unlink($file);
}

$action = $_GET['action'] ?? '';
$method = $_SERVER['REQUEST_METHOD'];

// Create room
if ($action === 'create' && $method === 'POST') {
    $roomId = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789'), 0, 6);
    $roomData = [
        'id' => $roomId,
        'offer' => null,
        'answer' => null,
        'host_ice' => [],
        'guest_ice' => [],
        'created_at' => time()
    ];
    file_put_contents("$roomsDir/$roomId.json", json_encode($roomData));
    echo json_encode(['roomId' => $roomId]);
    exit;
}

// Host sends offer
if ($action === 'send-offer' && $method === 'POST') {
    $roomId = $_POST['roomId'];
    $filePath = "$roomsDir/$roomId.json";
    if (!file_exists($filePath)) { echo 'error'; exit; }
    $data = json_decode(file_get_contents($filePath), true);
    $data['offer'] = $_POST['offer'];
    file_put_contents($filePath, json_encode($data));
    echo 'ok';
    exit;
}

// Guest checks for offer
if ($action === 'get-offer' && $method === 'GET') {
    $roomId = $_GET['roomId'];
    $filePath = "$roomsDir/$roomId.json";
    if (!file_exists($filePath)) { echo json_encode(['error' => 'not found']); exit; }
    $data = json_decode(file_get_contents($filePath), true);
    echo json_encode(['offer' => $data['offer']]);
    exit;
}

// Guest sends answer
if ($action === 'send-answer' && $method === 'POST') {
    $roomId = $_POST['roomId'];
    $filePath = "$roomsDir/$roomId.json";
    if (!file_exists($filePath)) { echo 'error'; exit; }
    $data = json_decode(file_get_contents($filePath), true);
    $data['answer'] = $_POST['answer'];
    file_put_contents($filePath, json_encode($data));
    echo 'ok';
    exit;
}

// Host checks for answer
if ($action === 'get-answer' && $method === 'GET') {
    $roomId = $_GET['roomId'];
    $filePath = "$roomsDir/$roomId.json";
    if (!file_exists($filePath)) { echo json_encode(['error' => 'not found']); exit; }
    $data = json_decode(file_get_contents($filePath), true);
    echo json_encode(['answer' => $data['answer']]);
    exit;
}

// Host sends ICE candidate
if ($action === 'send-host-ice' && $method === 'POST') {
    $roomId = $_POST['roomId'];
    $filePath = "$roomsDir/$roomId.json";
    if (!file_exists($filePath)) exit;
    $data = json_decode(file_get_contents($filePath), true);
    $data['host_ice'][] = $_POST['candidate'];
    file_put_contents($filePath, json_encode($data));
    echo 'ok';
    exit;
}

// Guest gets host ICE
if ($action === 'get-host-ice' && $method === 'GET') {
    $roomId = $_GET['roomId'];
    $filePath = "$roomsDir/$roomId.json";
    if (!file_exists($filePath)) { echo json_encode([]); exit; }
    $data = json_decode(file_get_contents($filePath), true);
    echo json_encode(['ice' => $data['host_ice']]);
    exit;
}

// Guest sends ICE
if ($action === 'send-guest-ice' && $method === 'POST') {
    $roomId = $_POST['roomId'];
    $filePath = "$roomsDir/$roomId.json";
    if (!file_exists($filePath)) exit;
    $data = json_decode(file_get_contents($filePath), true);
    $data['guest_ice'][] = $_POST['candidate'];
    file_put_contents($filePath, json_encode($data));
    echo 'ok';
    exit;
}

// Host gets guest ICE
if ($action === 'get-guest-ice' && $method === 'GET') {
    $roomId = $_GET['roomId'];
    $filePath = "$roomsDir/$roomId.json";
    if (!file_exists($filePath)) { echo json_encode([]); exit; }
    $data = json_decode(file_get_contents($filePath), true);
    echo json_encode(['ice' => $data['guest_ice']]);
    exit;
}

// Delete room
if ($action === 'delete' && $method === 'POST') {
    $roomId = $_POST['roomId'];
    $filePath = "$roomsDir/$roomId.json";
    if (file_exists($filePath)) unlink($filePath);
    echo 'ok';
    exit;
}

$autoRoomId = isset($_GET['room']) ? preg_replace('/[^a-z0-9]/', '', $_GET['room']) : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>OpenMeet - Working Video Call</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #1a1a2e;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }
        .app {
            max-width: 900px;
            width: 100%;
            background: #0f0f1a;
            border-radius: 28px;
            overflow: hidden;
            box-shadow: 0 25px 50px -12px rgba(0,0,0,0.5);
        }
        .header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            padding: 18px 20px;
            text-align: center;
            color: white;
        }
        .header h1 { font-size: 1.5rem; display: flex; align-items: center; justify-content: center; gap: 8px; }
        .videos {
            display: flex;
            flex-wrap: wrap;
            gap: 16px;
            padding: 20px;
        }
        .video-container {
            flex: 1;
            min-width: 250px;
            position: relative;
            background: #1a1a2e;
            border-radius: 20px;
            overflow: hidden;
            aspect-ratio: 4/3;
        }
        video {
            width: 100%;
            height: 100%;
            object-fit: cover;
            background: #1a1a2e;
        }
        .video-label {
            position: absolute;
            bottom: 12px;
            left: 12px;
            background: rgba(0,0,0,0.6);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            backdrop-filter: blur(4px);
        }
        .controls {
            display: flex;
            gap: 12px;
            justify-content: center;
            padding: 20px;
            flex-wrap: wrap;
            border-top: 1px solid #2a2a3a;
        }
        button {
            padding: 12px 24px;
            font-size: 14px;
            font-weight: 600;
            border: none;
            border-radius: 40px;
            cursor: pointer;
            transition: all 0.2s;
            font-family: inherit;
        }
        button:active { transform: scale(0.96); }
        .btn-primary { background: linear-gradient(135deg, #667eea, #764ba2); color: white; }
        .btn-success { background: #25d366; color: #1a1a2e; }
        .btn-danger { background: #e74c3c; color: white; }
        input {
            padding: 12px 20px;
            font-size: 14px;
            border: 2px solid #2a2a3a;
            border-radius: 40px;
            outline: none;
            background: #1a1a2e;
            color: white;
            width: 200px;
            text-align: center;
        }
        input:focus { border-color: #667eea; }
        .room-link {
            background: #1a1a2e;
            border-radius: 40px;
            padding: 12px 20px;
            margin: 10px 20px;
            word-break: break-all;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
        }
        .room-link span { color: #a0a0c0; font-size: 13px; }
        .copy-btn { background: #667eea; padding: 8px 16px; font-size: 12px; }
        .status {
            text-align: center;
            padding: 10px;
            font-size: 13px;
            color: #a0a0c0;
        }
        @media (max-width: 600px) {
            .videos { flex-direction: column; }
            input { width: 100%; }
        }
    </style>
</head>
<body>
<div class="app">
    <div class="header">
        <h1>🎥 OpenMeet</h1>
        <p style="font-size: 12px; opacity: 0.9;">Working Video Calls - Share link, click join</p>
    </div>
    <div class="videos">
        <div class="video-container">
            <video id="localVideo" autoplay muted playsinline></video>
            <div class="video-label">You</div>
        </div>
        <div class="video-container">
            <video id="remoteVideo" autoplay playsinline></video>
            <div class="video-label" id="remoteLabel">Waiting...</div>
        </div>
    </div>
    <div id="controlsArea"></div>
    <div id="statusArea" class="status">Ready</div>
</div>

<script>
    const localVideo = document.getElementById('localVideo');
    const remoteVideo = document.getElementById('remoteVideo');
    const remoteLabel = document.getElementById('remoteLabel');
    const controlsArea = document.getElementById('controlsArea');
    const statusArea = document.getElementById('statusArea');

    let localStream = null;
    let peerConnection = null;
    let currentRoomId = null;
    let isHost = false;
    let pollInterval = null;

    const config = {
        iceServers: [
            { urls: 'stun:stun.l.google.com:19302' },
            { urls: 'stun:stun1.l.google.com:19302' }
        ]
    };

    function showStatus(msg, isError = false) {
        statusArea.innerHTML = msg;
        statusArea.style.color = isError ? '#e74c3c' : '#a0a0c0';
    }

    async function copyToClipboard(text) {
        try {
            await navigator.clipboard.writeText(text);
            showStatus('✅ Link copied!');
            setTimeout(() => showStatus(''), 1500);
        } catch(e) {}
    }

    async function deleteRoom() {
        if (currentRoomId) {
            await fetch('?action=delete', { method: 'POST', body: new URLSearchParams({ roomId: currentRoomId }) });
        }
    }

    async function endCall() {
        if (pollInterval) clearInterval(pollInterval);
        if (peerConnection) {
            peerConnection.close();
            peerConnection = null;
        }
        if (localStream) {
            localStream.getTracks().forEach(t => t.stop());
            localStream = null;
        }
        localVideo.srcObject = null;
        remoteVideo.srcObject = null;
        remoteLabel.innerText = 'Call ended';
        await deleteRoom();
        currentRoomId = null;
        showStartScreen();
    }

    async function startLocalStream() {
        try {
            localStream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
            localVideo.srcObject = localStream;
            return true;
        } catch (err) {
            showStatus('❌ Camera/Mic access denied: ' + err.message, true);
            return false;
        }
    }

    function showStartScreen() {
        controlsArea.innerHTML = `
            <div style="display: flex; flex-direction: column; gap: 16px; padding: 0 20px 20px;">
                <button id="createBtn" class="btn-primary" style="padding: 14px; font-size: 16px;">📞 Create New Call</button>
                <div style="text-align: center; color: #a0a0c0;">OR</div>
                <input type="text" id="joinInput" placeholder="Enter 6-digit Room ID">
                <button id="joinBtn" class="btn-success">🔗 Join Call</button>
            </div>
        `;
        document.getElementById('createBtn')?.addEventListener('click', createRoom);
        document.getElementById('joinBtn')?.addEventListener('click', () => {
            const input = document.getElementById('joinInput');
            if (input.value.trim()) joinRoom(input.value.trim());
            else showStatus('Enter a Room ID', true);
        });
        showStatus('Create a new call or enter a Room ID to join');
    }

    async function createRoom() {
        showStatus('Creating room...');
        try {
            const res = await fetch('?action=create', { method: 'POST' });
            const data = await res.json();
            if (data.roomId) {
                currentRoomId = data.roomId;
                isHost = true;
                const ok = await startLocalStream();
                if (ok) {
                    await initHostConnection();
                    showCallUI();
                }
            } else {
                showStatus('Failed to create room', true);
            }
        } catch (err) {
            showStatus('Network error: ' + err.message, true);
        }
    }

    async function joinRoom(roomId) {
        currentRoomId = roomId;
        isHost = false;
        showStatus('Joining room ' + roomId + '...');
        
        const ok = await startLocalStream();
        if (!ok) return;
        
        // Check if room exists and get offer
        try {
            const res = await fetch(`?action=get-offer&roomId=${currentRoomId}`);
            const data = await res.json();
            
            if (data.error || !data.offer) {
                showStatus('Room not found or invalid. Create a new call.', true);
                currentRoomId = null;
                return;
            }
            
            await initGuestConnection(data.offer);
            showCallUI();
        } catch (err) {
            showStatus('Failed to join: ' + err.message, true);
            currentRoomId = null;
        }
    }

    async function initHostConnection() {
        peerConnection = new RTCPeerConnection(config);
        
        // Add local tracks
        localStream.getTracks().forEach(track => {
            peerConnection.addTrack(track, localStream);
        });
        
        // Handle remote track
        peerConnection.ontrack = (event) => {
            if (remoteVideo.srcObject !== event.streams[0]) {
                remoteVideo.srcObject = event.streams[0];
                remoteLabel.innerText = 'Connected';
                showStatus('📞 Both connected!');
            }
        };
        
        // Handle ICE candidates
        peerConnection.onicecandidate = (event) => {
            if (event.candidate) {
                fetch('?action=send-host-ice', {
                    method: 'POST',
                    body: new URLSearchParams({ roomId: currentRoomId, candidate: JSON.stringify(event.candidate) })
                });
            }
        };
        
        // Create offer
        const offer = await peerConnection.createOffer();
        await peerConnection.setLocalDescription(offer);
        
        // Send offer to server
        await fetch('?action=send-offer', {
            method: 'POST',
            body: new URLSearchParams({ roomId: currentRoomId, offer: JSON.stringify(offer) })
        });
        
        showStatus('Waiting for someone to join... Share the link!');
        remoteLabel.innerText = 'Waiting for guest...';
        
        // Poll for answer
        pollInterval = setInterval(async () => {
            if (!currentRoomId || !peerConnection) return;
            
            const res = await fetch(`?action=get-answer&roomId=${currentRoomId}`);
            const data = await res.json();
            
            if (data.answer && peerConnection.currentDescription?.type !== 'answer') {
                await peerConnection.setRemoteDescription(JSON.parse(data.answer));
                showStatus('Guest joined! Connecting...');
            }
            
            // Get guest ICE candidates
            const iceRes = await fetch(`?action=get-guest-ice&roomId=${currentRoomId}`);
            const iceData = await iceRes.json();
            if (iceData.ice) {
                for (let candidate of iceData.ice) {
                    try {
                        await peerConnection.addIceCandidate(JSON.parse(candidate));
                    } catch(e) {}
                }
            }
        }, 1500);
    }

    async function initGuestConnection(offerStr) {
        peerConnection = new RTCPeerConnection(config);
        
        localStream.getTracks().forEach(track => {
            peerConnection.addTrack(track, localStream);
        });
        
        peerConnection.ontrack = (event) => {
            if (remoteVideo.srcObject !== event.streams[0]) {
                remoteVideo.srcObject = event.streams[0];
                remoteLabel.innerText = 'Connected';
                showStatus('📞 Connected!');
            }
        };
        
        peerConnection.onicecandidate = (event) => {
            if (event.candidate) {
                fetch('?action=send-guest-ice', {
                    method: 'POST',
                    body: new URLSearchParams({ roomId: currentRoomId, candidate: JSON.stringify(event.candidate) })
                });
            }
        };
        
        // Set remote offer
        const offer = JSON.parse(offerStr);
        await peerConnection.setRemoteDescription(offer);
        
        // Create answer
        const answer = await peerConnection.createAnswer();
        await peerConnection.setLocalDescription(answer);
        
        // Send answer to server
        await fetch('?action=send-answer', {
            method: 'POST',
            body: new URLSearchParams({ roomId: currentRoomId, answer: JSON.stringify(answer) })
        });
        
        showStatus('Connected! Waiting for video...');
        
        // Poll for host ICE candidates
        pollInterval = setInterval(async () => {
            if (!currentRoomId || !peerConnection) return;
            
            const iceRes = await fetch(`?action=get-host-ice&roomId=${currentRoomId}`);
            const iceData = await iceRes.json();
            if (iceData.ice) {
                for (let candidate of iceData.ice) {
                    try {
                        await peerConnection.addIceCandidate(JSON.parse(candidate));
                    } catch(e) {}
                }
            }
        }, 1500);
    }

    function showCallUI() {
        const shareUrl = window.location.origin + window.location.pathname + '?room=' + currentRoomId;
        
        controlsArea.innerHTML = `
            <div class="room-link">
                <span>${shareUrl}</span>
                <button id="copyLinkBtn" class="copy-btn">📋 Copy Link</button>
            </div>
            <div style="display: flex; gap: 12px; justify-content: center; padding: 0 20px 20px;">
                <button id="endCallBtn" class="btn-danger">📞 End Call</button>
            </div>
        `;
        document.getElementById('copyLinkBtn')?.addEventListener('click', () => copyToClipboard(shareUrl));
        document.getElementById('endCallBtn')?.addEventListener('click', endCall);
        
        if (isHost) {
            showStatus('📢 Share the link above to let others join!');
        }
    }

    // Auto-join if room in URL
    const urlRoomId = new URLSearchParams(window.location.search).get('room');
    if (urlRoomId) {
        setTimeout(() => joinRoom(urlRoomId), 500);
    } else {
        showStartScreen();
    }

    window.addEventListener('beforeunload', () => {
        if (currentRoomId) deleteRoom();
    });
</script>
</body>
</html>