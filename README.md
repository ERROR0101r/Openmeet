
# OPENMEET

### Simple 1-on-1 Video Calling App | No Account | No Time Limit | No Data Mining

<div align="center">
  <img src="https://img.shields.io/badge/Status-Production_Ready-00C853?style=for-the-badge">
  <img src="https://img.shields.io/badge/Platform-Any%20Browser-0078D4?style=for-the-badge">
  <img src="https://img.shields.io/badge/Backend-PHP-777BB4?style=for-the-badge">
  <img src="https://img.shields.io/badge/WebRTC-P2P-FF6B6B?style=for-the-badge">
  <img src="https://img.shields.io/badge/File-Name-openmeet.php-4F5B93?style=for-the-badge">

  [![Telegram](https://img.shields.io/badge/Telegram-@ERROR0101risback-26A5E4?style=for-the-badge)](https://t.me/ERROR0101risback)
  [![Instagram](https://img.shields.io/badge/Instagram-@fahad0101r-E4405F?style=for-the-badge)](https://instagram.com/fahad0101r)
  [![GitHub](https://img.shields.io/badge/GitHub-ERROR0101r-181717?style=for-the-badge)](https://github.com/ERROR0101r)

  <p><strong>Developer: @ERROR0101risback</strong></p>
  <p><em>Version: 2.0.0 | Production Ready</em></p>
</div>

---

## 📋 TABLE OF CONTENTS

- [Why OpenMeet?](#why-openmeet)
- [Features](#features)
- [Quick Setup](#quick-setup)
- [Tech Stack](#tech-stack)
- [File Structure](#file-structure)
- [How to Use](#how-to-use)
- [API Endpoints](#api-endpoints)
- [Security Features](#security-features)
- [Deployment Guide](#deployment-guide)
- [Browser Support](#browser-support)
- [Commands List](#commands-list)
- [Troubleshooting](#troubleshooting)
- [Report Bugs](#report-bugs)
- [Developer Contact](#developer-contact)
- [License](#license)

---

## WHY OPENMEET?

**The Problem with Zoom / Google Meet / Microsoft Teams:**

- You need to create an account
- Calls have time limits (40 minutes on free tier)
- Your video and audio may be recorded and stored
- Companies mine your data for AI training
- Requires installing heavy desktop apps
- Privacy concerns with cloud processing

**How OpenMeet Solves This:**

- No account required - just share a link
- No time limits - call as long as you want
- Peer-to-peer encryption - no servers see your video
- Zero data storage - nothing saved anywhere
- Works in browser - no installation needed
- 100% private - your call, your data

**Comparison:**

| Feature | Zoom | Google Meet | Microsoft Teams | OpenMeet |
|---------|------|-------------|-----------------|----------|
| Account Required | Yes | Yes | Yes | No |
| Time Limit | 40 mins (free) | 60 mins | 60 mins | Unlimited |
| Installation | Required | Browser | Required | Browser Only |
| End-to-End Encrypted | No | No | No | Yes |
| Data Mining | Yes | Yes | Yes | No |
| Self-Hostable | No | No | No | Yes |
| Cost | Free/Paid | Free | Free/Paid | Free |

---

## FEATURES

**🎥 Video Calling**
- Real-time video communication
- Peer-to-peer WebRTC connection
- Automatic quality adjustment
- Mirror effect for natural view
- Full screen support

**🎤 Audio Features**
- Crystal clear audio quality
- Automatic gain control
- Echo cancellation
- Noise suppression
- Mute/unmute toggle

**🔗 Link Sharing**
- One-click room creation
- Shareable room links
- No password required
- Direct join from URL
- Auto-detects room from link

**🔒 Privacy & Security**
- Peer-to-peer encryption
- No server-side recording
- No data persistence
- No account required
- Anonymous calling
- Rooms auto-delete after 1 hour

**📱 Cross Platform**
- Works on any device
- Mobile responsive design
- No app installation
- Chrome, Firefox, Safari support
- Edge and Opera supported

**⚡ Performance**
- Lightweight (under 100KB)
- Fast connection establishment
- Low bandwidth usage
- No server processing
- Instant connection

---

## QUICK SETUP

**File Name:** `openmeet.php`

**One Command Deployment:**

```bash
# Clone the repository
git clone https://github.com/ERROR0101r/Openmeet.git
cd Openmeet

# Or just download the file
wget https://raw.githubusercontent.com/ERROR0101r/Openmeet/main/openmeet.php
```

PHP Server (No Database Required):

```bash
# Using PHP built-in server
php -S 0.0.0.0:8080

# Then open: http://localhost:8080/openmeet.php
```

Upload to Shared Hosting:

1. Upload openmeet.php to your public_html folder
2. Set permissions to 644
3. Access https://yourdomain.com/openmeet.php
4. Create rooms and share links instantly

Docker Setup:

```bash
# Create Dockerfile
cat > Dockerfile <<EOF
FROM php:8.2-apache
COPY openmeet.php /var/www/html/index.php
RUN chown -R www-data:www-data /var/www/html
EXPOSE 80
EOF

# Build and run
docker build -t openmeet .
docker run -d -p 8080:80 openmeet
```

Requirements:

· PHP 7.4 or higher (any version works)
· No database required
· No special extensions needed
· HTTPS recommended for camera access
· 1MB disk space
· Write permission for /rooms directory (auto-created)

---

TECH STACK

Backend (Signaling Server)

· Language: PHP 7.4+
· Storage: JSON files (no database)
· Features: Room creation, offer/answer exchange, ICE candidate relay

Frontend (Client)

· HTML5: Semantic structure, responsive design
· CSS3: Modern gradients, flexbox, animations
· JavaScript: ES6+, async/await, WebRTC API
· WebRTC: RTCPeerConnection, getUserMedia, ICE candidates

WebRTC Components Used

Component Purpose
STUN Server stun:stun.l.google.com:19302 - IP discovery
ICE Interactive Connectivity Establishment
SDP Session Description Protocol
DTLS Datagram Transport Layer Security
SRTP Secure Real-time Transport Protocol

No External Dependencies

· No jQuery
· No Bootstrap
· No React/Vue
· No CDN requirements (self-contained)

---

FILE STRUCTURE

```
Openmeet/
│
├── openmeet.php                 # Complete application (single file)
│
├── rooms/                       # Auto-created directory
│   ├── abc123.json             # Room data (auto-deleted after 1 hour)
│   └── def456.json             # Another active room
│
├── Dockerfile                   # Docker container configuration
└── README.md                   # This documentation
```

Room JSON Structure:

```json
{
    "id": "abc123",
    "offer": "v=0...",           // Host's SDP offer
    "answer": "v=0...",          // Guest's SDP answer
    "host_ice": [],              // Host's ICE candidates
    "guest_ice": [],             // Guest's ICE candidates
    "created_at": 1700000000     // Unix timestamp
}
```

---

HOW TO USE

Step-by-Step Guide

Person A (Host):

1. Open https://yourdomain.com/openmeet.php in your browser
2. Click "📞 Create New Call"
3. Allow camera and microphone access when prompted
4. Copy the generated link (e.g., https://yourdomain.com/openmeet.php?room=abc123)
5. Share this link with Person B via any messaging app

Person B (Joiner):

1. Click the link shared by Person A
2. The page loads automatically and requests camera/mic access
3. Allow permissions when prompted
4. You're instantly connected to the video call!

During the Call:

Button Action
Copy Link Copies the room link to clipboard
End Call Disconnects and ends the call

Pro Tips:

· Use headphones to avoid audio echo
· Close other tabs using camera for better performance
· Wired internet connection gives best quality
· Both users need good upload speed (1Mbps+)
· Rooms expire automatically after 1 hour of inactivity

---

API ENDPOINTS

All endpoints are relative to openmeet.php

Endpoint Method Description Parameters
?action=create POST Create new room None
?action=send-offer POST Host sends SDP offer roomId, offer
?action=get-offer GET Guest retrieves offer roomId
?action=send-answer POST Guest sends SDP answer roomId, answer
?action=get-answer GET Host retrieves answer roomId
?action=send-host-ice POST Host sends ICE candidate roomId, candidate
?action=get-host-ice GET Guest retrieves host ICE roomId
?action=send-guest-ice POST Guest sends ICE candidate roomId, candidate
?action=get-guest-ice GET Host retrieves guest ICE roomId
?action=delete POST Delete room roomId

cURL Examples:

```bash
# Create a room
curl -X POST https://yourdomain.com/openmeet.php?action=create
# Response: {"roomId":"abc123"}

# Get offer (guest joining)
curl "https://yourdomain.com/openmeet.php?action=get-offer&roomId=abc123"
# Response: {"offer":"v=0..."}

# Send answer
curl -X POST https://yourdomain.com/openmeet.php?action=send-answer \
  -d "roomId=abc123&answer=v=0..."

# Delete room
curl -X POST https://yourdomain.com/openmeet.php?action=delete \
  -d "roomId=abc123"
```

---

SECURITY FEATURES

Security Architecture

Layer Protection
Transport WebRTC uses DTLS 1.2 encryption
Media SRTP (Secure RTP) for video/audio
Signaling No sensitive data stored
Room Data Auto-deleted after 1 hour
IP Address Only visible to peer (P2P)
No Logging No persistent storage of calls

Privacy Guarantees

· ✅ No account creation required
· ✅ No email collection
· ✅ No call recording
· ✅ No analytics tracking
· ✅ No third-party cookies
· ✅ No data mining
· ✅ No cloud processing
· ✅ Rooms self-destruct after 1 hour

What OpenMeet Does NOT Store

· User names or identities
· Call history
· Video/audio content
· IP addresses (not logged)
· Any personal information
· Room data after 1 hour

Best Practices for Secure Calls

1. Share room links via encrypted messaging (Signal, WhatsApp, Telegram)
2. Use HTTPS for production deployments
3. Don't share room links publicly on social media
4. Rooms expire automatically - no cleanup needed
5. End call when conversation is complete

---

DEPLOYMENT GUIDE

Deploy to Shared Hosting (cPanel, DirectAdmin)

1. Upload openmeet.php to your public_html folder
2. No database setup required
3. No configuration needed
4. Ensure PHP 7.4+ is enabled
5. Access via https://yourdomain.com/openmeet.php
6. The rooms folder will be auto-created with write permissions

Deploy to VPS (Ubuntu/Debian)

```bash
# Install PHP
sudo apt update
sudo apt install php php-cli

# Download OpenMeet
wget https://raw.githubusercontent.com/ERROR0101r/Openmeet/main/openmeet.php

# Run with PHP
php -S 0.0.0.0:8080
```

Deploy with Nginx + PHP-FPM

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/openmeet;
    
    index openmeet.php;
    
    location / {
        try_files $uri $uri/ /openmeet.php?$args;
    }
    
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php7.4-fpm.sock;
    }
}
```

Deploy with Apache (.htaccess)

```apache
<Files "openmeet.php">
    SetHandler application/x-httpd-php
</Files>

<FilesMatch "\.json$">
    Order Allow,Deny
    Deny from all
</FilesMatch>
```

Deploy to Free Hosting (Works on):

· 000webhost
· InfinityFree
· AwardSpace
· FreeHosting.com
· ByetHost
· Scripthosting

---

BROWSER SUPPORT

Browser Version Support
Google Chrome 72+ ✅ Full Support
Mozilla Firefox 66+ ✅ Full Support
Safari 13+ ✅ Full Support
Microsoft Edge 79+ ✅ Full Support
Opera 60+ ✅ Full Support
Brave All ✅ Full Support
Samsung Internet 12+ ✅ Full Support
UC Browser Latest ⚠️ May work
Internet Explorer Any ❌ Not Supported

Mobile Browser Support

Platform Minimum Version
iOS Safari iOS 13.4+
Android Chrome Android 8+
Samsung Internet Full support
Firefox for Android Full support

---

COMMANDS LIST

Local Development

```bash
# Start PHP built-in server
php -S localhost:8080

# Start on all network interfaces
php -S 0.0.0.0:8080

# With custom port
php -S localhost:3000

# Specify file
php -S localhost:8080 -f openmeet.php
```

Docker Commands

```bash
# Build Docker image
docker build -t openmeet .

# Run container
docker run -d -p 8080:80 openmeet

# Stop container
docker stop $(docker ps -q --filter "ancestor=openmeet")

# View logs
docker logs -f container_name

# Remove container
docker rm container_name
```

File Management

```bash
# Create rooms directory manually (auto-created but can do manually)
mkdir rooms
chmod 755 rooms

# Clean old rooms manually
find rooms -name "*.json" -mmin +60 -delete

# Check rooms
ls -la rooms/
```

Testing Commands

```bash
# Check if server is running
curl -I http://localhost:8080/openmeet.php

# Test room creation
curl -X POST http://localhost:8080/openmeet.php?action=create

# Monitor rooms directory
watch -n 1 'ls -la rooms/ 2>/dev/null || echo "rooms/ not created yet"'
```

---

TROUBLESHOOTING

Problem Solution
Camera not working Grant permission in browser, check HTTPS
No video from other person Both need good internet, check firewall
Room not found error Room expired after 1 hour, create new
Can't create room Check rooms directory write permission
Call won't connect Both need WebRTC compatible browser
Audio echo Use headphones, reduce speaker volume
Poor video quality Close other bandwidth-heavy apps
Mobile not working Use Chrome or Safari on mobile
HTTPS required error Deploy with SSL certificate
STUN server failed Add more STUN servers in config

Common Error Fixes:

```bash
# Permission denied for rooms directory
chmod 755 rooms
chown www-data:www-data rooms

# PHP version too old
php -v  # Should be 7.4+

# Check if file is accessible
curl -I https://yourdomain.com/openmeet.php
```

---

REPORT BUGS

```
If you find any bug, issue, or problem:

1. Check if camera/mic permissions are granted
2. Try in a different browser
3. Check if both users are on HTTPS (except localhost)
4. Check browser console for errors (F12)
5. Check rooms directory permissions (must be writable)
6. Verify PHP version (7.4+)
7. Contact developer with:
   - Browser and version
   - Operating system
   - Steps to reproduce
   - Error message (if any)
   - Screenshot of browser console

Your reports help improve OpenMeet!
```

Known Limitations:

· Can only handle 2 participants (1-on-1 only)
· May not work on strict corporate firewalls
· Requires good internet connection for quality
· No recording feature
· No screen sharing
· HTTPS required for production (except localhost)

---

DEVELOPER CONTACT

<div align="center">
  <p><strong>Name:</strong> ERROR0101risback / Fahad</p>
  <p>
    <a href="https://t.me/ERROR0101risback">Telegram</a> •
    <a href="https://instagram.com/fahad0101r">Instagram</a> •
    <a href="https://github.com/ERROR0101r">GitHub</a>
  </p>
  <p><strong>File Name:</strong> openmeet.php</p>
</div>

---

REPOSITORY

· GitHub: https://github.com/ERROR0101r/Openmeet
· Download ZIP: https://github.com/ERROR0101r/Openmeet/archive/refs/heads/main.zip
· Raw File: https://raw.githubusercontent.com/ERROR0101r/Openmeet/main/openmeet.php
· Report Issues: https://github.com/ERROR0101r/Openmeet/issues
· Star on GitHub: ⭐ If you find this useful!

---

LICENSE

```
MIT License

Copyright (c) 2026 ERROR0101risback

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

---

<div align="center">
  <h3>🚀 One Link. One Click. One Call. 🚀</h3>
  <p><i>Made with ❤️ in Kashmir by @ERROR0101risback</i></p>

  <p>
    <a href="https://t.me/ERROR0101risback">Telegram</a> •
    <a href="https://instagram.com/fahad0101r">Instagram</a> •
    <a href="https://github.com/ERROR0101r">GitHub</a>
  </p>

  <p>© 2026 OpenMeet | Version 2.0.0 Stable</p>
  <p>Simple Peer-to-Peer Video Calling | No Account | No Time Limit | No Data Mining</p>
  <p><strong>File Name:</strong> openmeet.php</p>
</div>
