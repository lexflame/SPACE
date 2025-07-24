<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>SPACE</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        html, body {
          margin: 0;
          height: 100%;
          background: #000;
          overflow-x: hidden;
          font-family: 'Orbitron', sans-serif;
          /*color: #00faff;*/
          cursor: none !important;
        }
        body * {
            cursor: none !important;
        }

        canvas {
          position: fixed;
          top: 0;
          left: 0;
          z-index: 0;
          pointer-events: none;
        }

        .layer {
          position: fixed;
          width: 100%;
          height: 100%;
          top: 0;
          left: 0;
          background-size: cover;
          background-position: center;
          z-index: 1;
        }

        .content {
          position: relative;
          z-index: 10;
          height: 400vh;
          display: flex;
          align-items: center;
          justify-content: center;
          flex-direction: column;
          color: #00faff;
          text-align: center;
        }

        h1 {
          font-size: 4rem;
          text-shadow: 0 0 10px #00faff, 0 0 30px #00faff;
        }

        p {
          font-size: 1.2rem;
          color: #b0fefe;
        }
        html, body { min-height: 100vh; background: transparent; }
        body { position: relative; overflow-x: hidden; }
        .parallax-bg {
            position: fixed;
            z-index: 0;
            inset: 0;
            width: 100vw;
            height: 120vh;
            background: linear-gradient(110deg,#1e2327 20%,#23272b 70%,#181c1f 100%);
            overflow: hidden;
        }
        .bubble {
            position: absolute;
            border-radius: 50%;
            opacity: 0.18;
            filter: blur(2px);
            transition: box-shadow 0.2s, transform 0.5s cubic-bezier(.18,.81,.46,.98);
            will-change: transform;
            pointer-events: none;
        }
        /* Индивидуальные цвета пузырей */
        .bubble1 { background:#ffffff; left:10vw;top:60vh;width:280px;height:280px;}
        .bubble2 { background:#46ffd5; right:8vw;top:30vh;width:210px;height:210px;}
        .bubble3 { background:#ffe783; left:38vw;top:10vh;width:140px;height:140px;}
        .bubble4 { background:#0fff; right:30vw;top:80vh;width:110px;height:110px;}
        .bubble5 { background:#46ffd5; right:22vw;top:60vh;width:80px;height:80px;}
        .bubble6 { background:#ffffff; left:70vw;top:10vh;width:130px;height:130px;}
        /* Эффект волны */
        .bubble.wave {
            box-shadow: 0 0 45px 22px #fff5, 0 0 0px 0px #fff4;
            opacity: 0.28 !important;
            animation: wave-bubble 0.7s cubic-bezier(.7,.01,.36,.87);
        }
        @keyframes wave-bubble {
            0%   { box-shadow: 0 0 0 0 transparent, 0 0 0 0 transparent; opacity: 0.38;  }
            40%  { box-shadow: 0 0 90px 42px #fff8, 0 0 0 0 #fff2; opacity: 0.65; }
            100% { box-shadow: 0 0 0 0 transparent, 0 0 0 0 transparent; opacity: 0.18; }
        }
        .container { position:relative; z-index:2; }
        .card {
            background: #23272b;
            border: none;
            box-shadow: 0 0.1rem 0.3rem rgba(0,0,0,0.3);
            transition:
                transform 0.29s cubic-bezier(.29,.97,.43,1.02),
                box-shadow 0.20s,
                background 0.23s;
            z-index: 1;
        }
        .card-link { color: inherit; text-decoration: none; display: block; height: 100%; }

        .card-link:focus .card,
        .card-link:hover .card {
            background: #31353a;
            transform: translateY(-9px) scale(1.045);
            box-shadow: 0 1.5rem 2.5rem #00000085, 0 2px 14px #ffc10755;
            outline: none;
        }
        .card-link:focus-visible .card { outline: 0.15em solid #46ffd5; }

        .icon-big {
            width: 3em; height: 3em; margin-bottom: 0.6em;
            color: #ffc107;
            fill: currentColor;
            transition: color 0.2s, filter 0.24s, transform 0.16s;
        }
        .card-link:focus .icon-big,
        .card-link:hover .icon-big {
            color: #45ffd5;
            filter: drop-shadow(0 0 7px #42f9ff66);
            transform: scale(1.11) translateY(-5px) rotate(-8deg);
        }
        .card-title {
            color: #ffc107;
            letter-spacing: 1px; font-weight: bold;
            transition: color 0.17s, text-shadow 0.23s;
        }
        .card-link:focus .card-title,
        .card-link:hover .card-title {
            color: #45ffd5;
            text-shadow: 0 1px 3px #3beebc80;
        }
        .card-text {
            transition: color 0.19s;
        }
        .card-link:focus .card-text,
        .card-link:hover .card-text {
            color: #fff;
        }
        /* Анимация выделения всей строки карточки (при клике) */
        .card-link.active .card {
            box-shadow: 0 0 50px 18px #45ffd53a, 0 3px 18px #ffc10744;
            background: #223c32;
        }
        /* Волна под соответствующим пузырем */
        .bubble.wave {
            opacity: 0.3 !important;
        }
        /* Для плавности навигации */
        .wave { transition: opacity .24s, box-shadow .3s;}
        /* Для тача - увеличение области hover*/
        @media (hover: none) {
            .card-link:active .card { transform: scale(1.02); }
            .icon-big { will-change: transform; }
        }

        .shine-title {
          display: inline-block;
          position: relative;
          font-family: inherit;
          font-size: 2.7rem;
          letter-spacing: 1px;
          color: #f3f3f5;
          background: 
            linear-gradient(110deg, #d1cecb 0%, #eee 33%, #000000 38%, #7c7c7c 52%, #e5e2cf 60%, #fff4e4 77%, #ffe 100%);
          background-size: 200% 100%;
          background-position: -100% 0;
          -webkit-background-clip: text;
          -webkit-text-fill-color: transparent;
                  background-clip: text;
                  text-fill-color: transparent;
          /* Для поддержки в Edge */
          filter: drop-shadow(0 2px 14px #fff1);
          animation: shine-move 2.8s cubic-bezier(.61,0,.55,1) infinite forwards;
          transition: filter 0.3s, text-shadow 0.3s, letter-spacing 0.2s;
          will-change: background-position, filter;
        }
        @keyframes shine-move {
          0%   { background-position: -100% 0;}
          60%  { background-position: 120% 0;}
          100% { background-position: 120% 0;}
        }
        .shine-title:hover, .shine-title:focus {
          animation-duration: 1.4s;
          letter-spacing: 2.5px;
          filter: drop-shadow(0 2px 28px #fff7) blur(0.2px);
          background-size: 300% 120%;
          text-shadow: 0 1px 4px #fff6, 0 3px 22px #d0ad5c99;
        }
        canvas#canvas {
            z-index: 3;
            opacity: 0.2;
        }
    </style>
  <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@600&display=swap" rel="stylesheet">
</head>
<body>
   
    <div id="page-fadeout"></div>
    <style>
        #page-fadeout {
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background: #000;
          opacity: 0;
          pointer-events: none;
          z-index: 9999;
          transition: opacity 0.6s ease;
        }
        #page-fadeout.show {
          opacity: 1;
          pointer-events: auto;
        }
    </style>

    <canvas id="canvas"></canvas>

    <!-- Параллакс-слой -->
    <div class="parallax-bg">
        <div class="bubble bubble1" data-bubble="tasks"></div>
        <div class="bubble bubble3" data-bubble="notes"></div>
        
        <div class="bubble bubble2" data-bubble="maps"></div>
        <div class="bubble bubble4" data-bubble="debugger"></div>
        
        <div class="bubble bubble3" data-bubble="notes"></div>
        
        <div class="bubble bubble4" data-bubble="debugger"></div>
        <div class="bubble bubble2" data-bubble="maps"></div>
        
        <div class="bubble bubble5" data-bubble="picker"></div>
        
        <div class="bubble bubble6" data-bubble="extra"></div>
    </div>

    <div class="container py-5">
        <h1 class="shine-title text-center mb-5 text-light">SPACE</h1>
        <div class="row g-4">
            <!-- Задачи -->
            <div class="col-12 col-sm-6 col-lg-4">
                <a href="/tasks" class="card-link" data-bubble="tasks">
                    <div class="card h-100 text-center p-4">
                        <div class="card-body">
                            <svg class="icon-big" viewBox="0 0 16 16"><path d="M2 2.5A.5.5 0 0 1 2.5 2h11a.5.5 0 0 1 .5.5v11a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11zM3 3v10h10V3H3zm2.854 2.146a.5.5 0 0 1 .07.638l-.058.07-2 2a.5.5 0 0 1-.765-.638l.058-.07 1.147-1.147-1.146-1.147a.5.5 0 0 1 .638-.765l.07.058 2 2zm4.292 4.292a.5.5 0 0 1 .638.058l.07.07a.5.5 0 0 1-.638.765l-.07-.058-2-2a.5.5 0 0 1 .07-.765l.07.058L10 9.293zm2.853-5.647a.5.5 0 0 1 .07.638l-.07.07-2 2a.5.5 0 0 1-.765-.639l.07-.07L12.293 4.354a.5.5 0 0 1 .707-.707z"/></svg>
                            <h5 class="card-title">MakerTask</h5>
                            <p class="card-text text-secondary">Управляйте своими задачами.</p>
                        </div>
                    </div>
                </a>
            </div>
            <!-- Карты -->
            <div class="col-12 col-sm-6 col-lg-4">
                <a href="/tacmap" class="card-link" data-bubble="maps">
                    <div class="card h-100 text-center p-4">
                        <div class="card-body">
                            <svg class="icon-big" viewBox="0 0 16 16"><path d="M15.817.113A.5.5 0 0 1 16 .5v13.981a.5.5 0 0 1-.683.474l-4.53-1.78-5.573 1.792a.5.5 0 0 1-.348 0L.183 15.887A.5.5 0 0 1 0 15.5V1.519a.5.5 0 0 1 .683-.474l4.53 1.78 5.573-1.792a.5.5 0 0 1 .348 0l5.683 2.08zm-5.657 1.818-5 1.606v11.042l5-1.607V1.93zm1 .011v11.04l4 1.572V2.514l-4-1.573zM1 2.514v11.041l4 1.573V3.928L1 2.514z"/></svg>
                            <h5 class="card-title">TACMap</h5>
                            <p class="card-text text-secondary">Работайте с картами и маршрутами.</p>
                        </div>
                    </div>
                </a>
            </div>
            <!-- Заметки -->
            <div class="col-12 col-sm-6 col-lg-4">
                <a href="<?= site_url('notes') ?>" class="card-link" data-bubble="notes">
                    <div class="card h-100 text-center p-4">
                        <div class="card-body">
                            <svg class="icon-big" viewBox="0 0 16 16"><path d="M4 1.5a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-13zm7 0H5v13h6v-13z"/></svg>
                            <h5 class="card-title">Заметки</h5>
                            <p class="card-text text-secondary">Храните и просматривайте заметки.</p>
                        </div>
                    </div>
                </a>
            </div>
            <!-- Отладчик -->
            <div class="col-12 col-sm-6 col-lg-4">
                <a href="interact" class="card-link" data-bubble="debugger">
                    <div class="card h-100 text-center p-4">
                        <div class="card-body">
                            <svg class="icon-big" viewBox="0 0 16 16"><path d="M3.204 11a1 1 0 1 1-1.414-1.415l7.071-7.07a1 1 0 0 1 1.415 1.415L3.204 11zm9.192-9.192a1 1 0 0 1 1.415 1.415L4.74 13.04a1 1 0 1 1-1.415-1.415zm0 0"/><path d="M6 13.5a1.5 1.5 0 1 1 3 0v1A1.5 1.5 0 0 1 6 14.5v-1zm4-3a1.5 1.5 0 1 1 3 0v1a1.5 1.5 0 0 1-3 0v-1z"/></svg>
                            <h5 class="card-title">InterAct</h5>
                            <p class="card-text text-secondary">Анализируйте и исправляйте ошибки.</p>
                        </div>
                    </div>
                </a>
            </div>
            <!-- Викер -->
            <div class="col-12 col-sm-6 col-lg-4">
                <a href="<?= site_url('picker') ?>" class="card-link" data-bubble="picker">
                    <div class="card h-100 text-center p-4">
                        <div class="card-body">
                            <svg class="icon-big" viewBox="0 0 16 16"><path d="M8 2a6 6 0 1 1 0 12A6 6 0 0 1 8 2zm0-1a7 7 0 1 0 0 14A7 7 0 0 0 8 1zm3 7a1 1 0 1 1-2 0 1 1 0 0 1 2 0z"/></svg>
                            <h5 class="card-title">Викер</h5>
                            <p class="card-text text-secondary">Интерактивный выбор параметров.</p>
                        </div>
                    </div>
                </a>
            </div>
            <!-- Доп. секция -->
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="card h-100 text-center p-4">
                    <div class="card-body">
                        <svg class="icon-big" viewBox="0 0 16 16"><path d="M8 0a2 2 0 0 1 2 2H6a2 2 0 0 1 2-2zm6 9a2 2 0 0 1-2 2v2a2 2 0 1 1-2 2H6a2 2 0 1 1-2-2v-2a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2c0-1.104.896-2 2-2h4c1.104 0 2 .896 2 2a2 2 0 0 1 2 2v3z"/></svg>
                        <h5 class="card-title">Еще...</h5>
                        <p class="card-text text-secondary">Добавьте свой модуль.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <canvas id="bg"></canvas>

    <div class="content">
      <h1>Sci-Fi Vectorverse</h1>
      <p>Welcome to the intersection of technology and infinity</p>
    </div>

    <script>
      const canvas = document.getElementById("bg");
      const ctx = canvas.getContext("2d");

      let width = innerWidth;
      let height = innerHeight;
      canvas.width = width;
      canvas.height = height;

      window.addEventListener("resize", () => {
        width = innerWidth;
        height = innerHeight;
        canvas.width = width;
        canvas.height = height;
      });

      // Создание точек
      const nodes = [];
      const maxNodes = 100;
      for (let i = 0; i < maxNodes; i++) {
        nodes.push({
          x: Math.random() * width,
          y: Math.random() * height,
          vx: (Math.random() - 0.5) * 0.2,
          vy: (Math.random() - 0.5) * 0.2
        });
      }

      function drawNetwork() {
        ctx.clearRect(0, 0, width, height);
        ctx.fillStyle = "#00faff";
        ctx.strokeStyle = "rgba(0,255,255,0.2)";
        ctx.lineWidth = 0.5;

        // Движение и соединение
        for (let i = 0; i < maxNodes; i++) {
          const n = nodes[i];
          n.x += n.vx;
          n.y += n.vy;

          if (n.x < 0 || n.x > width) n.vx *= -1;
          if (n.y < 0 || n.y > height) n.vy *= -1;

          ctx.beginPath();
          ctx.arc(n.x, n.y, 1.5, 0, Math.PI * 2);
          ctx.fill();

          for (let j = i + 1; j < maxNodes; j++) {
            const m = nodes[j];
            const dx = n.x - m.x;
            const dy = n.y - m.y;
            const dist = Math.sqrt(dx * dx + dy * dy);
            if (dist < 100) {
              ctx.beginPath();
              ctx.moveTo(n.x, n.y);
              ctx.lineTo(m.x, m.y);
              ctx.stroke();
            }
          }
        }
      }

      function animate() {
        drawNetwork();
        requestAnimationFrame(animate);
      }

      animate();

      // Параллакс при scroll'е
      window.addEventListener('scroll', () => {
        const scroll = window.scrollY;
        canvas.style.transform = `translateY(${scroll * 0.3}px)`;
      });
    </script>
    <!-- Интерактив & Параллакс -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    const canvas_light = document.getElementById("canvas");
    const ctx_light = canvas_light.getContext("2d");
    let width_light = window.innerWidth;
    let height_light = window.innerHeight;
    canvas_light.width = width_light;
    canvas_light.height = height_light;

    let targetX = width_light / 2;
    let targetY = height_light / 2;
    let mouseX = targetX, mouseY = targetY;

    let points = Array.from({ length: 20 }, () => ({
      x: Math.random() * width_light,
      y: Math.random() * height_light
    }));

    function regeneratePoints() {
      points = Array.from({ length: 20 }, () => ({
        x: Math.random() * width_light,
        y: Math.random() * height_light
      }));
    }

    setInterval(regeneratePoints, 5000); // Обновляем точки каждые 5 секунд

    window.addEventListener("resize", () => {
      width_light = window.innerWidth;
      height_light = window.innerHeight;
      canvas_light.width = width_light;
      canvas_light.height = height_light;
    });

    document.addEventListener("mousemove", (e) => {
      mouseX = e.clientX;
      mouseY = e.clientY;
    });

    const getColor = () => {
      const h = ((mouseX + mouseY) / 10) % 360;
      return `hsl(${h}, 100%, 70%)`;
    };

    function drawGlowLine(x1, y1, x2, y2, color) {
      ctx_light.shadowColor = color;
      ctx_light.shadowBlur = 15;
      ctx_light.strokeStyle = color;
      ctx_light.beginPath();
      ctx_light.moveTo(x1, y1);
      ctx_light.lineTo(x2, y2);
      ctx_light.stroke();
    }

    function draw() {
      ctx_light.clearRect(0, 0, width_light, height_light);
      targetX += (mouseX - targetX) * 0.1;
      targetY += (mouseY - targetY) * 0.1;

      // glowing grid
      ctx_light.strokeStyle = "rgba(0, 255, 255, 0.1)";
      for (let x = 0; x < width_light; x += 50) {
        ctx_light.beginPath();
        ctx_light.moveTo(x, 0);
        ctx_light.lineTo(x, height_light);
        ctx_light.stroke();
      }
      for (let y = 0; y < height_light; y += 50) {
        ctx_light.beginPath();
        ctx_light.moveTo(0, y);
        ctx_light.lineTo(width_light, y);
        ctx_light.stroke();
      }

      const color = getColor();

      points.forEach(p => {
        drawGlowLine(p.x, p.y, targetX, targetY, color);
        points.forEach(p2 => {
          if (p !== p2 && Math.random() < 0.005) {
            drawGlowLine(p.x, p.y, p2.x, p2.y, "rgba(255,255,255,0.05)");
          }
        });
      });

      ctx_light.fillStyle = color;
      ctx_light.shadowBlur = 20;
      ctx_light.shadowColor = color;
      ctx_light.beginPath();
      ctx_light.arc(targetX, targetY, 5, 0, Math.PI * 2);
      ctx_light.fill();

      requestAnimationFrame(draw);
    }

    draw();
  </script>
    <script>
        // Параллакс пузырей
        const bubbles = Array.from(document.querySelectorAll('.bubble'));
        // Ключ: соответствует data-bubble атрибуту
        const bubbleMap = {};
        bubbles.forEach(b => {
            bubbleMap[b.dataset.bubble] = b;
        });

        window.addEventListener('scroll', function() {
            let scrolled = window.scrollY || window.pageYOffset;
            bubbles.forEach((bubble, i) => {
                // Задаем разную инерцию для каждого пузыря
                let speed = 0.09 + 0.028 * (i + 1);
                let dx = (i%2===0 ? 1 : -1) * (scrolled * speed * 0.34);
                let dy = scrolled * speed;
                bubble.style.transform = `translate(${dx}px,${dy}px)`;
            });
        });

        // Параллакс по мыши (горизонтальный, более легкий)
        document.addEventListener('mousemove', function(e){
            let w = window.innerWidth,
                h = window.innerHeight,
                mx = e.clientX / w - 0.5,
                my = e.clientY / h - 0.5;
            bubbles.forEach((bubble,i) => {
                let tx = (i+1) * mx * 18 * ((i%2===0)?1:-1);
                let ty = (i+2) * my * 13;
                let prevTrans = bubble.style.transform || '';
                // extract scroll Y if present:
                let m = prevTrans.match(/,(-?\d+)px$$/);
                let scrollY = m ? parseInt(m[1]) : 0;
                bubble.style.transform = `translate(${tx}px,${scrollY===0?ty:scrollY}px)`;
            });
        });

        // Волна по клику — "волнует" пузырь под карточкой
        document.querySelectorAll('.card-link[data-bubble]').forEach(link => {
            link.addEventListener('click', function(e){
                // Волна
                let key = link.dataset.bubble;
                let b = bubbleMap[key];
                if(b) {
                    b.classList.remove('wave'); // сбросить если что-то мигало
                    void b.offsetWidth;         // reflow for restart
                    b.classList.add('wave');
                    setTimeout(()=>b.classList.remove('wave'), 800);
                }
                // Подсветка карточки (снимет подсветку с других, если они остались)
                document.querySelectorAll('.card-link.active').forEach(a => a.classList.remove('active'));
                link.classList.add('active');
                // Продолжить переход через 100 мс (красиво)
                setTimeout(()=>window.location=link.href, 120);
                e.preventDefault();
            });
        });

        // Если войти с клавиатуры — подсветить
        document.querySelectorAll('.card-link').forEach(link => {
            link.addEventListener('focus', function() {
                document.querySelectorAll('.card-link.active').forEach(a => a.classList.remove('active'));
                link.classList.add('active');
            });
            link.addEventListener('blur', function(){
                link.classList.remove('active');
            });
        });

        // Если на мобильном/тач, подсветим при тапе — простая поддержка для тача
        document.querySelectorAll('.card-link').forEach(link => {
            link.addEventListener('touchstart', function() {
                link.classList.add('active');
            });
            link.addEventListener('touchend', function() {
                setTimeout(()=>link.classList.remove('active'), 450);
            });
        });
    </script>
    <script>
      // Переход при клике по ссылке
      $(document).on('click', 'a[href]:not([target="_blank"]):not([href^="#"])', function(e) {
        e.preventDefault();
        const href = $(this).attr('href');
        $('#page-fadeout').addClass('show');
        setTimeout(() => {
          window.location.href = href;
        }, 600);
      });

      // Переход при обновлении/перезагрузке
      window.addEventListener('beforeunload', () => {
        document.getElementById('page-fadeout').classList.add('show');
      });
    </script>
</body>
</html>
