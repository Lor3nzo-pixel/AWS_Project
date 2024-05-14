<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Progetto AWS</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        h1, h2, h3 {
            color: #007bff;
            margin-top: 0;
        }

        p {
            line-height: 1.6;
        }

        pre {
            background-color: #f1f1f1;
            padding: 10px;
            border-radius: 5px;
            overflow-x: auto;
        }

        code {
            font-family: Consolas, monospace;
            font-size: 14px;
        }

        .logout-btn {
            display: block;
            width: 100%;
            max-width: 200px;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: #dc3545;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
        }

        .logout-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Progetto AWS: Docker</h1>

        <div id="section1">
            <h2>Installazione di Docker</h2>
            <p>Per iniziare, vediamo come installare Docker, una piattaforma di containerizzazione che semplifica lo sviluppo, la distribuzione e la gestione delle applicazioni.</p>
            <p><strong>Passaggi principali:</strong></p>
            <ul>
                <li><strong>Aggiornamento delle repositories:</strong> Aggiorna le repositories di apt per ottenere le versioni più recenti dei pacchetti.</li>
                <li><strong>Installazione di Docker:</strong> Utilizza i seguenti comandi per installare Docker e Docker Compose sul tuo sistema:</li>
                <pre><code>sudo apt-get update
sudo apt-get install -y ca-certificates curl
sudo install -m 0755 -d /etc/apt/keyrings
sudo curl -fsSL https://download.docker.com/linux/ubuntu/gpg -o /etc/apt/keyrings/docker.asc
sudo chmod a+r /etc/apt/keyrings/docker.asc

echo \
"deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.asc] https://download.docker.com/linux/ubuntu \
$(. /etc/os-release &amp;&amp; echo "$VERSION_CODENAME") stable" | \
sudo tee /etc/apt/sources.list.d/docker.list &gt; /dev/null
sudo apt-get update</code></pre>
                <li><strong>Installazione di Docker e Docker Compose:</strong> Utilizza il seguente comando per installare Docker e Docker Compose:</li>
                <pre><code>sudo apt-get install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin</code></pre>
            </ul>
        </div>

        <div id="section2">
            <h2>Generazione di Certificati SSL self-signed</h2>
            <p>Successivamente, vedremo come generare certificati SSL self-signed utilizzando la suite OpenSSL. Questi certificati sono utili per fornire una connessione HTTPS sicura al server.</p>
            <p><strong>Passaggi principali:</strong></p>
            <ul>
                <li><strong>Creazione dei certificati:</strong> Utilizza OpenSSL per generare un certificato e una chiave SSL self-signed. Ecco il comando:</li>
                <pre><code>openssl req -nodes -new -x509 -keyout progetto/keys/ssl-cert-snakeoil.key -out progetto/keys/ssl-cert-snakeoil.pem</code></pre>
            </ul>
        </div>

        <div id="section3">
            <h2>Configurazione del Dockerfile</h2>
            <p>Un aspetto cruciale è la configurazione del Dockerfile, che determina come viene costruita un'immagine Docker. In questa sezione, discuteremo la configurazione di un Dockerfile per un ambiente PHP 8.2 Apache ottimizzato per la connettività MySQL e la sicurezza HTTPS.</p>
            <pre><code>FROM php:8.2-apache

COPY ./public-html/ /var/www/html
COPY ./keys/ssl-cert-snakeoil.key /etc/ssl/private
COPY ./keys/ssl-cert-snakeoil.pem /etc/ssl/certs

RUN sed -i '/&lt;\/VirtualHost&gt;/ i RewriteEngine On\nRewriteCond %{HTTPS} off\nRewriteRule (.*) https://%{HTTP_HOST}%{REQUEST_URI}' /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite
RUN a2enmod ssl
RUN a2ensite default-ssl

RUN docker-php-ext-install mysqli &amp;&amp; \
    docker-php-ext-enable mysqli</code></pre>
        </div>

        <div id="section4">
            <h2>Configurazione del compose.yaml</h2>
            <p>Il file compose.yaml è fondamentale per definire la struttura e la configurazione di un'applicazione multi-container. Vedremo come configurare questo file per avviare un ambiente con un web server Apache e un database MySQL, connessi tramite una rete Docker.</p>
            <pre><code>services:
    php-app:
        build: progetto
        depends_on:
            - db
        ports:
            - "80:80"
            - "443:443"
        networks:
            - net

    db:
        image: mysql:latest
        environment:
        MYSQL_DATABASE: database
        networks:
            - net
        volumes:
            - "./progetto/sql:/docker-entrypoint-initdb.d"

networks:
    net:</code></pre>
        </div>

        <div id="section5">
            <h2>Avviamento dei container</h2>
            <p>Infine, vedremo come avviare i container Docker utilizzando il comando "docker compose up -d". Questo comando avvia l'applicazione multi-container definita nel file compose.yaml.</p>
            <pre><code>sudo docker compose up -d</code></pre>
        </div>

        <div id="section6">
            <h2>Tecnologie utilizzate</h2>
            <p>Prima di concludere, è importante capire le tecnologie utilizzate in questo progetto:</p>
            <ul>
                <li><strong>Docker:</strong> Piattaforma di containerizzazione per lo sviluppo e la distribuzione di applicazioni.</li>
                <li><strong>OpenSSL:</strong> Suite per la gestione di certificati SSL.</li>
                <li><strong>PHP:</strong> Linguaggio di scripting ampiamente utilizzato per lo sviluppo web.</li>
                <li><strong>Apache:</strong> Server web open source.</li>
            </ul>
        </div>

        <a href="login.php" class="logout-btn">Esci</a>
    </div>

</body>
</html>
