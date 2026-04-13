<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tout Help - Accueil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .chat-modal {
            display: none;
            position: fixed;
            bottom: 100px;
            right: 20px;
            width: 350px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            z-index: 1000;
            overflow: hidden;
        }
        .chat-modal.active {
            display: block;
            animation: fadeInUp 0.3s ease;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .chat-header {
            background: #1a3c34;
            color: white;
            padding: 15px;
            text-align: center;
            font-weight: bold;
        }
        .chat-body {
            padding: 20px;
        }
        .chat-footer {
            background: #f3f4f6;
            padding: 10px;
            text-align: center;
            font-size: 12px;
            color: gray;
        }
        .robot-icon {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: #1a3c34;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            transition: transform 0.3s;
            z-index: 999;
        }
        .robot-icon:hover {
            transform: scale(1.1);
        }
        .robot-icon i {
            font-size: 30px;
            color: white;
        }
        .form-input, .form-textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 14px;
        }
        .btn-send {
            background: #1a3c34;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            font-weight: bold;
        }
        .btn-send:hover {
            background: #0f2b24;
        }
        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
            text-align: center;
        }
    </style>
</head>
<body class="bg-gray-50">

    <!-- En-tête avec logo et menu -->
    <header class="bg-white shadow-md sticky top-0 z-50">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/Design_sans_titre_3_-removebg-preview.png') }}" alt="Logo" class="h-12">
                <span class="text-xl font-bold text-green-900">TOUT HELP</span>
            </div>
            <nav class="hidden md:flex space-x-6">
                <a href="#" class="text-gray-700 hover:text-green-800">ACCUEIL</a>
                <a href="#" class="text-gray-700 hover:text-green-800">À PROPOS</a>
                <a href="#" class="text-gray-700 hover:text-green-800">EXPERTISE</a>
                <a href="#" class="text-gray-700 hover:text-green-800">CATALOGUE</a>
                <a href="#" class="text-gray-700 hover:text-green-800">BLOG</a>
                <a href="#" class="text-gray-700 hover:text-green-800">CONTACT</a>
            </nav>
            <div class="flex items-center space-x-4">
                <a href="#" class="bg-green-800 text-white px-4 py-2 rounded-full text-sm hover:bg-green-900">S'INSCRIRE</a>
            </div>
        </div>
    </header>

    <!-- Section héro -->
    <section class="relative bg-cover bg-center h-[500px]" style="background-image: url('{{ asset('images/krae0EbF.jpg') }}');">
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="relative container mx-auto px-4 h-full flex flex-col justify-center text-white">
            <h1 class="text-4xl md:text-6xl font-bold mb-4">ENSEMBLE FAISONS DE LA PERFORMANCE UNE CULTURE</h1>
            <p class="text-xl mb-6">Expert en Solutions RH et Accompagnement sur-mesure à Madagascar.</p>
            <div class="flex flex-wrap gap-4">
                <input type="text" placeholder="Trouver une formation..." class="px-4 py-3 rounded-lg w-64 text-gray-800">
                <button class="bg-green-700 px-6 py-3 rounded-lg hover:bg-green-800">Rechercher</button>
            </div>
        </div>
    </section>

    <!-- Icône robot flottant -->
    <div class="robot-icon" id="robotIcon">
        <i class="fas fa-robot"></i>
    </div>

    <!-- Modal de chat -->
    <div class="chat-modal" id="chatModal">
        <div class="chat-header">
            <i class="fas fa-headset mr-2"></i> Avez-vous des questions ?
        </div>
        <div class="chat-body">
            @if(session('message_success'))
                <div class="success-message">
                    {{ session('message_success') }}
                </div>
            @endif
            <form action="{{ route('contact.send') }}" method="POST">
                @csrf
                <input type="text" name="nom" placeholder="Votre nom" class="form-input" required>
                <input type="email" name="email" placeholder="Votre email" class="form-input" required>
                <input type="text" name="telephone" placeholder="Votre téléphone" class="form-input">
                <textarea name="message" rows="3" placeholder="Votre message..." class="form-textarea" required></textarea>
                <button type="submit" class="btn-send">Envoyer</button>
            </form>
        </div>
        <div class="chat-footer">
            Nous vous répondrons dans les plus brefs délais.
        </div>
    </div>

    <script>
        const robotIcon = document.getElementById('robotIcon');
        const chatModal = document.getElementById('chatModal');

        robotIcon.addEventListener('click', function() {
            chatModal.classList.toggle('active');
        });

        // Fermer le modal si on clique en dehors (optionnel)
        window.addEventListener('click', function(e) {
            if (!chatModal.contains(e.target) && !robotIcon.contains(e.target)) {
                chatModal.classList.remove('active');
            }
        });
    </script>
</body>
</html>