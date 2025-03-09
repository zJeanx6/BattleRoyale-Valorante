<?php 
require_once('header.php');
?>
<head>
    <title>Inicio - Battle Royale</title>
    <style>
        :root {
            --primary-color: #ff4655;
            --primary-hover: #e03e4e;
            --secondary-color: #00eaff;
            --secondary-hover: #00c4d6;
            --dark-bg: rgba(0, 0, 0, 0.8);
            --card-bg: rgba(20, 20, 30, 0.85);
            --text-color: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Rajdhani', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text-color);
            overflow-x: hidden;
            height: 100vh;
            width: 100vw;
            position: relative;
        }

        /* Video de fondo */
        #background-video {
            position: fixed;
            right: 0;
            bottom: 0;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            z-index: -100;
            background-size: cover;
            overflow: hidden;
        }

        .video-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(0,0,0,0.9) 0%, rgba(20,20,40,0.7) 100%);
            z-index: -99;
        }

        /* Contenedor principal */
        .container-fluid {
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 20px;
            position: relative;
            z-index: 1;
        }

        /* Avatar */
        .avatar-img {
            max-width: 200px;
            border-radius: 50%;
            border: 5px solid var(--secondary-color);
            box-shadow: 0 0 15px var(--secondary-color);
            position: relative;
        }

        .avatar-container {
            position: relative;
            display: inline-block;
        }

        .avatar-container::after {
            content: '';
            position: absolute;
            top: -10px;
            left: -10px;
            right: -10px;
            bottom: -10px;
            border-radius: 50%;
            border: 2px solid var(--secondary-color);
            opacity: 0.6;
            animation: pulse 2s infinite;
        }

        /* Tarjeta de perfil */
        .profile-card {
            background-color: var(--card-bg);
            padding: 25px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 0 20px rgba(0, 234, 255, 0.2);
            border: 1px solid rgba(0, 234, 255, 0.1);
            backdrop-filter: blur(5px);
        }

        .profile-card h2 {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 0 0 10px rgba(0, 234, 255, 0.5);
            letter-spacing: 1px;
        }

        .profile-card p {
            font-size: 1rem;
            opacity: 0.8;
            margin-bottom: 20px;
        }

        /* Botones */
        .btn-custom, .btn-avatar {
            background: linear-gradient(to right, rgba(0,0,0,0.7), rgba(0,0,0,0.9));
            color: var(--text-color);
            border: none;
            border-radius: 5px;
            padding: 12px 25px;
            font-size: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            margin: 5px;
            min-width: 180px;
        }

        .btn-custom::before, .btn-avatar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent, rgba(255,255,255,0.1), transparent);
            transform: translateX(-100%);
            transition: 0.5s;
        }

        .btn-custom:hover::before, .btn-avatar:hover::before {
            transform: translateX(100%);
        }

        .btn-custom {
            border: 2px solid var(--primary-color);
            box-shadow: 0 0 10px rgba(255, 70, 85, 0.5);
        }

        .btn-custom:hover {
            background-color: var(--primary-color);
            box-shadow: 0 0 15px rgba(255, 70, 85, 0.7);
        }

        .btn-avatar {
            border: 2px solid var(--secondary-color);
            box-shadow: 0 0 10px rgba(0, 234, 255, 0.5);
        }

        .btn-avatar:hover {
            background-color: var(--secondary-color);
            box-shadow: 0 0 15px rgba(0, 234, 255, 0.7);
            color: #000;
        }

        /* Animaciones */
        @keyframes pulse {
            0% {
                transform: scale(1);
                opacity: 0.6;
            }
            50% {
                transform: scale(1.1);
                opacity: 0.4;
            }
            100% {
                transform: scale(1);
                opacity: 0.6;
            }
        }

        .pulse-animation {
            animation: pulse-play 2s infinite;
        }

        @keyframes pulse-play {
            0% {
                box-shadow: 0 0 0 0 rgba(255, 70, 85, 0.7);
            }
            70% {
                box-shadow: 0 0 0 15px rgba(255, 70, 85, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(255, 70, 85, 0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .avatar-img {
                max-width: 150px;
            }
            
            .profile-card h2 {
                font-size: 1.8rem;
            }
            
            .profile-card {
                padding: 20px;
            }
            
            .btn-custom, .btn-avatar {
                padding: 10px 20px;
                min-width: 150px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .avatar-img {
                max-width: 120px;
            }
            
            .profile-card h2 {
                font-size: 1.5rem;
            }
            
            .profile-card {
                padding: 15px;
            }
            
            .btn-custom, .btn-avatar {
                padding: 8px 15px;
                font-size: 0.8rem;
                min-width: 130px;
                margin: 3px;
            }
            
            .container-fluid {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <!-- Inicio Video de fondo -->
    <video autoplay muted loop id="background-video">
        <source src="../img/Videos/indexJu.mp4" type="video/mp4">
        Tu navegador no soporta el elemento de video.
    </video>
    <div class="video-overlay"></div>
    <!-- Fin Video de fondo -->

    <!-- Inicio Contenedor principal -->
    <div class="container-fluid h-100 d-flex flex-column justify-content-between">
            <div class="row mt-3">
                <div class="col-12 text-center">
                    <div class="avatar-container">
                        <img src="<?php echo $avatar['img']; ?>" class="avatar-img" alt="Avatar">
                    </div>
                </div>
            </div>
        <!-- Fin Contenedor principal -->
            
        <!-- Inicio Contenedor de perfil -->
            <div class="row mt-3">
                <div class="col-12 text-center">
                    <div class="profile-card">
                        <h2><?php echo $usuario['nom_usu']; ?></h2>
                        <img src="<?php echo $nivel['img']; ?>" alt="Nivel" style="max-height: 50px;">
                        <p>ID: <?php echo $usuario['doc']; ?></p>
                        <a href="perfil/perfil.php" class="btn btn-custom">Ver Perfil</a>
                        <a href="../include/exit.php" class="btn btn-custom">Cerrar Sesion</a>
                    </div>
                </div>
            </div>
        <!-- Fin Contenedor de perfil -->

        <!-- Inicio Contenedor de botones -->
            <div class="row mt-3">
                <div class="col-12 text-center">
                    <a href="perfil/cambiar_avatar.php" class="btn btn-avatar">Cambiar Avatar</a>
                    <a href="perfil/cambiar_armamento.php" class="btn btn-avatar">Cambiar Armamento</a>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-12 text-center">
                    <div class="dropdown">
                        <a href="sala/jugar.php" class="btn btn-custom pulse-animation">Jugar</a>
                    </div>
                </div>
            </div>
        <!-- Fin Contenedor de botones -->
    </div>
    <!-- Fin Contenedor principal -->       
</body>
</html>