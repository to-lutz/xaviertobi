<?php session_start(); ?>


<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8" />
  <title>FitGear Shop – Kategorisiert</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Inter', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #121212;
      color: #fff;
    }
    header {
      background-color: #1f1f1f;
      padding: 20px;
      text-align: center;
      font-size: 1.5em;
      font-weight: bold;
      border-bottom: 2px solid #333;
    }
    nav {
      display: flex;
      justify-content: space-around;
      background-color: #222;
      padding: 10px 0;
      font-size: 1em;
    }
    nav a {
      color: #aaa;
      text-decoration: none;
    }
    nav a:hover {
      color: #fff;
    }
    .container {
      padding: 20px;
    }
    .category-title {
      text-align: center;
      font-size: 1.5em;
      margin: 40px 0 20px;
      border-bottom: 1px solid #444;
      padding-bottom: 10px;
    }
    .product-grid {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
    }
    .product {
      background-color: #1e1e1e;
      border-radius: 12px;
      padding: 15px;
      display: flex;
      align-items: center;
      gap: 15px;
      width: calc(50% - 10px);
      box-sizing: border-box;
    }
    .product img {
      width: 100px;
      height: 100px;
      object-fit: contain;
      border-radius: 8px;
    }
    .product-details {
      flex-grow: 1;
    }
    .product-title {
      font-size: 1.2em;
      font-weight: 600;
    }
    .product-price {
      color: #66cc66;
      margin-top: 5px;
    }
    .btn {
      padding: 10px 15px;
      background-color: #4CAF50;
      border: none;
      color: white;
      border-radius: 6px;
      cursor: pointer;
      margin-top: 10px;
    }
    .load-more {
      margin: 20px auto;
      display: block;
    }
  </style>

<style>
.modal {
  position: fixed;
  top: 0; left: 0; width: 100%; height: 100%;
  background: rgba(0,0,0,0.8);
  display: flex; justify-content: center; align-items: center;
  z-index: 1000;
}
.modal-content {
  background: white;
  padding: 20px;
  max-width: 500px;
  border-radius: 8px;
  color: black;
  text-align: center;
  position: relative;
}
.modal-content img {
  max-width: 100%;
  height: auto;
}
.modal-content .close {
  position: absolute;
  right: 10px; top: 10px;
  font-size: 24px; cursor: pointer;
}
#cart-toast {
  position: fixed;
  top: 20px; left: 50%;
  transform: translateX(-50%);
  background: black;
  color: white;
  padding: 12px 24px;
  border-radius: 8px;
  font-weight: bold;
  z-index: 2000;
  display: none;
}
.cart-preview {
  position: absolute;
  right: 10px;
  top: 60px;
  background: #222;
  padding: 10px;
  border: 1px solid #444;
  border-radius: 6px;
  z-index: 1500;
}
.cart-preview div {
  color: white;
  font-size: 14px;
  margin-bottom: 5px;
}
</style>
</head>
<body style="background-color:#121212; color:#e0e0e0;" style="background-color:#121212; color:#e0e0e0;">
  <header>🛒 FitGear Mobile Shop</header>
  <nav>
     <a href="index.php">Shop</a>
    <a href="checkout.html">Kasse</a>
    <a href="faq.html">FAQ</a>
    <a href="timer.html">Angebot</a>
    
  
    <?php if (isset($_SESSION['user'])): ?>
  <div style="position: absolute; right: 10px; top: 12px; text-align: right;">
<?php if (isset($_SESSION['user'])): ?>
  <div class="dropdown">
    <span style="color:white; font-weight:bold; cursor:pointer;">👤 <?= htmlspecialchars($_SESSION['user']) ?> ⌄</span>
    <div class="dropdown-content">
      <a href="logout.php" style="color:white;">Logout</a>
    </div>
  </div>
  <div style="margin-top:8px;">
    🛒 <span id="cart-count" style="background:#4CAF50;padding:2px 8px;border-radius:10px;">0</span>
  </div>
<?php else: ?>
  <a href="login.php" style="color:white; margin-right:10px;">Anmelden</a>
  <a href="register.php" style="color:white;">Registrieren</a>
<?php endif; ?>
</div>
<?php else: ?>
  <div style="position: absolute; right: 10px; top: 12px;">
    <a href="login.php" style="color: white; margin-right: 10px;">Anmelden</a>
    <a href="register.php" style="color: white;">Registrieren</a>
  </div>
<?php endif; ?>

  </nav>

  
  <div class="container">


 <!-- Erweiterunf vom Shop mit top angebote  -->
<div class="top-seller" style="margin-top: 40px;">
  <h2 style="color:#4CAF50; margin-bottom: 15px;">🔥 Top-Seller Produkte</h2>
  <div id="product-slider" class="product-slider"></div>
</div>

<style>
  .product-slider {
    display: flex;
    gap: 10px;
    overflow: hidden;
    position: relative;
    min-height: 180px;
    transition: all 0.5s ease-in-out;
  }

  .product-box {
    background: #2a2a2a;
    padding: 20px;
    border-radius: 8px;
    flex: 1 0 calc(20% - 10px);
    text-align: center;
    color: white;
    box-shadow: 0 0 8px #000;
    opacity: 0;
    transform: translateX(100%);
    transition: all 0.6s ease;
  }

  .product-box.active {
    opacity: 1;
    transform: translateX(0);
  }
</style>

<script>
  const products = Array.from({ length: 15 }, (_, i) => `Produkt ${i + 1}`);
  let currentIndex = 0;
  const container = document.getElementById("product-slider");

  function showTopSellers() {
    container.innerHTML = "";

    const items = products.slice(currentIndex, currentIndex + 5);
    items.forEach((name, i) => {
      const div = document.createElement("div");
      div.className = "product-box";
      div.textContent = name;
      container.appendChild(div);

      // Aktiviere Slide-Animation nacheinander
      setTimeout(() => div.classList.add("active"), 100 + i * 100);
    });

    currentIndex = (currentIndex + 5) % products.length;
  }

  showTopSellers();
  setInterval(showTopSellers, 8000);
</script>







    <div class="category-title">📱 Handy und Zubehör</div>
    <div class="product-grid" id="container__handy_und_zubehr"></div>
    <button class="btn load-more" id="btn__handy_und_zubehr">Mehr anzeigen</button>
    

    <div class="category-title">🖥️ PC Zubehör</div>
    <div class="product-grid" id="container__pc_zubehr"></div>
    <button class="btn load-more" id="btn__pc_zubehr">Mehr anzeigen</button>
    

    <div class="category-title">🚗 Auto Gadgets</div>
    <div class="product-grid" id="container__auto_gadgets"></div>
    <button class="btn load-more" id="btn__auto_gadgets">Mehr anzeigen</button>
    

    <div class="category-title">📦 Sonstiges</div>
    <div class="product-grid" id="container__sonstiges"></div>
    <button class="btn load-more" id="btn__sonstiges">Mehr anzeigen</button>
    

    <div class="category-title">🎧 Kopfhörer & Musikboxen</div>
    <div class="product-grid" id="container__kopfhrer__musikboxen"></div>
    <button class="btn load-more" id="btn__kopfhrer__musikboxen">Mehr anzeigen</button>
    
</div><script>
function addToCart(name, price) { alert(`${name} zum Warenkorb hinzugefügt!`); }

    const container__handy_und_zubehr_products = [{"title": "Ladekabel", "price": 15.14, "img": "https://via.placeholder.com/100?text=Ladekabel"}, {"title": "Hülle iPhone", "price": 2.25, "img": "https://via.placeholder.com/100?text=Hülle+iPhone"}, {"title": "Powerbank", "price": 106.41, "img": "https://via.placeholder.com/100?text=Powerbank"}, {"title": "Kfz-Ladegerät", "price": 6.27, "img": "https://via.placeholder.com/100?text=Kfz-Ladegerä"}, {"title": "Displayschutzfolie", "price": 106.38, "img": "https://via.placeholder.com/100?text=Displayschut"}, {"title": "Handyhülle transparent", "price": 46.08, "img": "https://via.placeholder.com/100?text=Handyhülle+t"}, {"title": "Handy-Kühlpad", "price": 64.13, "img": "https://via.placeholder.com/100?text=Handy-Kühlpa"}, {"title": "Lade-Dock", "price": 5.62, "img": "https://via.placeholder.com/100?text=Lade-Dock"}, {"title": "Handy-Armband", "price": 11.17, "img": "https://via.placeholder.com/100?text=Handy-Armban"}, {"title": "2-in-1 Kabel", "price": 5.33, "img": "https://via.placeholder.com/100?text=2-in-1+Kabel"}, {"title": "USB-Ladegerät", "price": 142.38, "img": "https://via.placeholder.com/100?text=USB-Ladegerä"}, {"title": "Handyhalterung", "price": 100.16, "img": "https://via.placeholder.com/100?text=Handyhalteru"}, {"title": "Ladekabel Auto", "price": 6.74, "img": "https://via.placeholder.com/100?text=Ladekabel+Au"}, {"title": "Handyhalterung fürs Auto", "price": 29.99, "img": "https://via.placeholder.com/100?text=Auto"}, {"title": "Powerbank 20000mAh", "price": 39.99, "img": "https://via.placeholder.com/100?text=Powerbank"}];
    let index__handy_und_zubehr = 0;
    const step__handy_und_zubehr = 10;
    const container__handy_und_zubehr = document.getElementById("container__handy_und_zubehr");
    const btn__handy_und_zubehr = document.getElementById("btn__handy_und_zubehr");

    function render__handy_und_zubehr() {
      const next = container__handy_und_zubehr_products.slice(index__handy_und_zubehr, index__handy_und_zubehr + step__handy_und_zubehr);
      next.forEach(p => {
        const div = document.createElement("div");
        div.className = "product";
        
div.innerHTML = `
  <a href="produkt-${p.title.toLowerCase().replaceAll(' ', '-').replaceAll('ä','ae').replaceAll('ö','oe').replaceAll('ü','ue').replaceAll('ß','ss')}.html" style='text-decoration: none; color: inherit;'>
    <div class="product-inner">
      <img src="${p.img}" alt="${p.title}" />
      <div class="product-title">${p.title}</div>
      <div class="product-price">${p.price.toFixed(2)} €</div>
      <div class="product-desc">${p.description || ""}</div>
    </div>
  </a>
`;

        container__handy_und_zubehr.appendChild(div);
      });
      index__handy_und_zubehr += step__handy_und_zubehr;
      if (index__handy_und_zubehr >= container__handy_und_zubehr_products.length) {
        btn__handy_und_zubehr.style.display = "none";
      }
    }
    btn__handy_und_zubehr.addEventListener("click", render__handy_und_zubehr);
    render__handy_und_zubehr();
    

    const container__pc_zubehr_products = [{"title": "USB-C Adapter", "price": 113.39, "img": "https://via.placeholder.com/100?text=USB-C+Adapte"}, {"title": "Tablet-Ständer", "price": 21.6, "img": "https://via.placeholder.com/100?text=Tablet-Ständ"}, {"title": "USB-Stick Musik", "price": 50.92, "img": "https://via.placeholder.com/100?text=USB-Stick+Mu"}, {"title": "USB Speaker", "price": 132.54, "img": "https://via.placeholder.com/100?text=USB+Speaker"}, {"title": "PC Lautsprecher", "price": 107.49, "img": "https://via.placeholder.com/100?text=PC+Lautsprec"}, {"title": "USB LED Lampe", "price": 21.27, "img": "https://via.placeholder.com/100?text=USB+LED+Lamp"}, {"title": "LED Monitor Light", "price": 5.05, "img": "https://via.placeholder.com/100?text=LED+Monitor+"}, {"title": "USB Nachtlicht", "price": 56.69, "img": "https://via.placeholder.com/100?text=USB+Nachtlic"}, {"title": "LED PC Leiste", "price": 70.54, "img": "https://via.placeholder.com/100?text=LED+PC+Leist"}, {"title": "USB Lichtband", "price": 35.84, "img": "https://via.placeholder.com/100?text=USB+Lichtban"}, {"title": "Mini-PC", "price": 64.64, "img": "https://via.placeholder.com/100?text=Mini-PC"}, {"title": "Bluetooth Tastatur", "price": 113.92, "img": "https://via.placeholder.com/100?text=Bluetooth+Ta"}, {"title": "USB-Hub", "price": 100.38, "img": "https://via.placeholder.com/100?text=USB-Hub"}, {"title": "USB Mikroskop", "price": 131.44, "img": "https://via.placeholder.com/100?text=USB+Mikrosko"}, {"title": "Maus mit Daumenrad", "price": 2.59, "img": "https://via.placeholder.com/100?text=Maus+mit+Dau"}, {"title": "USB Heizkissen", "price": 114.38, "img": "https://via.placeholder.com/100?text=USB+Heizkiss"}, {"title": "Gaming Maus", "price": 50.65, "img": "https://via.placeholder.com/100?text=Gaming+Maus"}, {"title": "RGB Mauspad", "price": 77.55, "img": "https://via.placeholder.com/100?text=RGB+Mauspad"}, {"title": "Mechanische Tastatur", "price": 142.83, "img": "https://via.placeholder.com/100?text=Mechanische+"}, {"title": "Maus Bungee", "price": 53.71, "img": "https://via.placeholder.com/100?text=Maus+Bungee"}, {"title": "Headset-Ständer", "price": 9.43, "img": "https://via.placeholder.com/100?text=Headset-Stän"}, {"title": "Joystick PC", "price": 4.74, "img": "https://via.placeholder.com/100?text=Joystick+PC"}, {"title": "USB Soundkarte", "price": 36.08, "img": "https://via.placeholder.com/100?text=USB+Soundkar"}, {"title": "Ergonomische Maus", "price": 141.43, "img": "https://via.placeholder.com/100?text=Ergonomische"}, {"title": "Gaming Maus", "price": 49.99, "img": "https://via.placeholder.com/100?text=Gaming"}];
    let index__pc_zubehr = 0;
    const step__pc_zubehr = 10;
    const container__pc_zubehr = document.getElementById("container__pc_zubehr");
    const btn__pc_zubehr = document.getElementById("btn__pc_zubehr");

    function render__pc_zubehr() {
      const next = container__pc_zubehr_products.slice(index__pc_zubehr, index__pc_zubehr + step__pc_zubehr);
      next.forEach(p => {
        const div = document.createElement("div");
        div.className = "product";
        
div.innerHTML = `
  <a href="produkt-${p.title.toLowerCase().replaceAll(' ', '-').replaceAll('ä','ae').replaceAll('ö','oe').replaceAll('ü','ue').replaceAll('ß','ss')}.html" style='text-decoration: none; color: inherit;'>
    <div class="product-inner">
      <img src="${p.img}" alt="${p.title}" />
      <div class="product-title">${p.title}</div>
      <div class="product-price">${p.price.toFixed(2)} €</div>
      <div class="product-desc">${p.description || ""}</div>
    </div>
  </a>
`;

        container__pc_zubehr.appendChild(div);
      });
      index__pc_zubehr += step__pc_zubehr;
      if (index__pc_zubehr >= container__pc_zubehr_products.length) {
        btn__pc_zubehr.style.display = "none";
      }
    }
    btn__pc_zubehr.addEventListener("click", render__pc_zubehr);
    render__pc_zubehr();
    

    const container__auto_gadgets_products = [{"title": "MagSafe Halterung", "price": 2.65, "img": "https://via.placeholder.com/100?text=MagSafe+Halt"}, {"title": "CarPlay Dongle", "price": 6.16, "img": "https://via.placeholder.com/100?text=CarPlay+Dong"}, {"title": "Smartphone-Halterung Fahrrad", "price": 21.04, "img": "https://via.placeholder.com/100?text=Smartphone-H"}, {"title": "Auto-Organizer", "price": 141.16, "img": "https://via.placeholder.com/100?text=Auto-Organiz"}, {"title": "Auto-Luftreiniger", "price": 83.5, "img": "https://via.placeholder.com/100?text=Auto-Luftrei"}, {"title": "Auto-Sitzhaken", "price": 41.39, "img": "https://via.placeholder.com/100?text=Auto-Sitzhak"}, {"title": "Getränkehalter", "price": 82.6, "img": "https://via.placeholder.com/100?text=Getränkehalt"}, {"title": "Auto-Duftspender", "price": 55.63, "img": "https://via.placeholder.com/100?text=Auto-Duftspe"}, {"title": "Auto-Staubsauger", "price": 32.96, "img": "https://via.placeholder.com/100?text=Auto-Staubsa"}, {"title": "Tablet-Halter Kopfstütze", "price": 3.31, "img": "https://via.placeholder.com/100?text=Tablet-Halte"}, {"title": "Luftbefeuchter Auto", "price": 40.06, "img": "https://via.placeholder.com/100?text=Luftbefeucht"}, {"title": "Lautsprecher Halterung", "price": 93.2, "img": "https://via.placeholder.com/100?text=Lautsprecher"}, {"title": "Auto-Bluetooth Speaker", "price": 1.52, "img": "https://via.placeholder.com/100?text=Auto-Bluetoo"}, {"title": "Tischhalter Monitor", "price": 5.84, "img": "https://via.placeholder.com/100?text=Tischhalter+"}, {"title": "Capture Card", "price": 14.06, "img": "https://via.placeholder.com/100?text=Capture+Card"}];
    let index__auto_gadgets = 0;
    const step__auto_gadgets = 10;
    const container__auto_gadgets = document.getElementById("container__auto_gadgets");
    const btn__auto_gadgets = document.getElementById("btn__auto_gadgets");

    function render__auto_gadgets() {
      const next = container__auto_gadgets_products.slice(index__auto_gadgets, index__auto_gadgets + step__auto_gadgets);
      next.forEach(p => {
        const div = document.createElement("div");
        div.className = "product";
        
div.innerHTML = `
  <a href="produkt-${p.title.toLowerCase().replaceAll(' ', '-').replaceAll('ä','ae').replaceAll('ö','oe').replaceAll('ü','ue').replaceAll('ß','ss')}.html" style='text-decoration: none; color: inherit;'>
    <div class="product-inner">
      <img src="${p.img}" alt="${p.title}" />
      <div class="product-title">${p.title}</div>
      <div class="product-price">${p.price.toFixed(2)} €</div>
      <div class="product-desc">${p.description || ""}</div>
    </div>
  </a>
`;

        container__auto_gadgets.appendChild(div);
      });
      index__auto_gadgets += step__auto_gadgets;
      if (index__auto_gadgets >= container__auto_gadgets_products.length) {
        btn__auto_gadgets.style.display = "none";
      }
    }
    btn__auto_gadgets.addEventListener("click", render__auto_gadgets);
    render__auto_gadgets();
    

    const container__sonstiges_products = [{"title": "Wireless Charger", "price": 100.75, "img": "https://via.placeholder.com/100?text=Wireless+Cha"}, {"title": "PopSocket", "price": 4.61, "img": "https://via.placeholder.com/100?text=PopSocket"}, {"title": "Selfie Stick", "price": 7.32, "img": "https://via.placeholder.com/100?text=Selfie+Stick"}, {"title": "Mini-Stativ", "price": 19.64, "img": "https://via.placeholder.com/100?text=Mini-Stativ"}, {"title": "Reinigungstuch", "price": 47.7, "img": "https://via.placeholder.com/100?text=Reinigungstu"}, {"title": "Gaming-Grip", "price": 47.5, "img": "https://via.placeholder.com/100?text=Gaming-Grip"}, {"title": "iPad Pencil Ersatz", "price": 125.97, "img": "https://via.placeholder.com/100?text=iPad+Pencil+"}, {"title": "Anti-Rutsch-Pad", "price": 65.39, "img": "https://via.placeholder.com/100?text=Anti-Rutsch-"}, {"title": "Multifunktions-Clip", "price": 21.63, "img": "https://via.placeholder.com/100?text=Multifunktio"}, {"title": "LED Innenraum", "price": 5.32, "img": "https://via.placeholder.com/100?text=LED+Innenrau"}, {"title": "Reifen-Luftdruckprüfer", "price": 22.35, "img": "https://via.placeholder.com/100?text=Reifen-Luftd"}, {"title": "Kofferraumnetz", "price": 79.12, "img": "https://via.placeholder.com/100?text=Kofferraumne"}, {"title": "Parkhilfe", "price": 50.73, "img": "https://via.placeholder.com/100?text=Parkhilfe"}, {"title": "Bluetooth FM-Transmitter", "price": 27.9, "img": "https://via.placeholder.com/100?text=Bluetooth+FM"}, {"title": "Dashcam", "price": 41.16, "img": "https://via.placeholder.com/100?text=Dashcam"}, {"title": "Sitzkissen", "price": 85.79, "img": "https://via.placeholder.com/100?text=Sitzkissen"}, {"title": "Scheibenabdeckung", "price": 8.73, "img": "https://via.placeholder.com/100?text=Scheibenabde"}, {"title": "Schmutzmatte", "price": 97.86, "img": "https://via.placeholder.com/100?text=Schmutzmatte"}, {"title": "Notfallhammer", "price": 7.07, "img": "https://via.placeholder.com/100?text=Notfallhamme"}, {"title": "Smart Key Cover", "price": 3.45, "img": "https://via.placeholder.com/100?text=Smart+Key+Co"}, {"title": "Gurtpolster", "price": 3.35, "img": "https://via.placeholder.com/100?text=Gurtpolster"}, {"title": "LED Projektor Logo", "price": 5.51, "img": "https://via.placeholder.com/100?text=LED+Projekto"}, {"title": "JBL GO", "price": 0.8, "img": "https://via.placeholder.com/100?text=JBL+GO"}, {"title": "Bose SoundLink", "price": 21.22, "img": "https://via.placeholder.com/100?text=Bose+SoundLi"}, {"title": "Anker SoundCore", "price": 19.31, "img": "https://via.placeholder.com/100?text=Anker+SoundC"}, {"title": "Marshall Emberton", "price": 7.97, "img": "https://via.placeholder.com/100?text=Marshall+Emb"}, {"title": "Sony XB13", "price": 61.38, "img": "https://via.placeholder.com/100?text=Sony+XB13"}, {"title": "Echo Dot", "price": 98.83, "img": "https://via.placeholder.com/100?text=Echo+Dot"}, {"title": "Subwoofer", "price": 19.98, "img": "https://via.placeholder.com/100?text=Subwoofer"}, {"title": "Soundbar", "price": 18.21, "img": "https://via.placeholder.com/100?text=Soundbar"}, {"title": "Radio mit BT", "price": 0.98, "img": "https://via.placeholder.com/100?text=Radio+mit+BT"}, {"title": "LED Strip 5m", "price": 53.45, "img": "https://via.placeholder.com/100?text=LED+Strip+5m"}, {"title": "Bewegungssensor Licht", "price": 5.38, "img": "https://via.placeholder.com/100?text=Bewegungssen"}, {"title": "RGB Tischlampe", "price": 26.38, "img": "https://via.placeholder.com/100?text=RGB+Tischlam"}, {"title": "LED Eckenleiste", "price": 7.35, "img": "https://via.placeholder.com/100?text=LED+Eckenlei"}, {"title": "Smart Glühbirne", "price": 98.08, "img": "https://via.placeholder.com/100?text=Smart+Glühbi"}, {"title": "LED Duschkopf", "price": 44.92, "img": "https://via.placeholder.com/100?text=LED+Duschkop"}, {"title": "Licht mit Fernbedienung", "price": 111.77, "img": "https://via.placeholder.com/100?text=Licht+mit+Fe"}, {"title": "Sonnenaufgangswecker", "price": 22.0, "img": "https://via.placeholder.com/100?text=Sonnenaufgan"}, {"title": "Laserprojektor", "price": 1.62, "img": "https://via.placeholder.com/100?text=Laserprojekt"}, {"title": "Lichterkette", "price": 9.8, "img": "https://via.placeholder.com/100?text=Lichterkette"}, {"title": "Touchlampe", "price": 4.76, "img": "https://via.placeholder.com/100?text=Touchlampe"}, {"title": "Schreibtisch-LED", "price": 92.88, "img": "https://via.placeholder.com/100?text=Schreibtisch"}, {"title": "LED Ringlicht", "price": 8.85, "img": "https://via.placeholder.com/100?text=LED+Ringlich"}, {"title": "Gaming LED Pads", "price": 81.79, "img": "https://via.placeholder.com/100?text=Gaming+LED+P"}, {"title": "WLAN Steckdose", "price": 146.6, "img": "https://via.placeholder.com/100?text=WLAN+Steckdo"}, {"title": "Timer Plug", "price": 2.35, "img": "https://via.placeholder.com/100?text=Timer+Plug"}, {"title": "Smart Plug", "price": 31.7, "img": "https://via.placeholder.com/100?text=Smart+Plug"}, {"title": "Stimmungslicht", "price": 50.02, "img": "https://via.placeholder.com/100?text=Stimmungslic"}, {"title": "Licht Panel", "price": 102.24, "img": "https://via.placeholder.com/100?text=Licht+Panel"}, {"title": "RGB Wanddeko", "price": 67.18, "img": "https://via.placeholder.com/100?text=RGB+Wanddeko"}, {"title": "Mini-Beamer", "price": 52.91, "img": "https://via.placeholder.com/100?text=Mini-Beamer"}, {"title": "Smartwatch", "price": 5.27, "img": "https://via.placeholder.com/100?text=Smartwatch"}, {"title": "TV Stick", "price": 17.22, "img": "https://via.placeholder.com/100?text=TV+Stick"}, {"title": "WiFi Repeater", "price": 5.48, "img": "https://via.placeholder.com/100?text=WiFi+Repeate"}, {"title": "Action Cam", "price": 145.88, "img": "https://via.placeholder.com/100?text=Action+Cam"}, {"title": "Webcam Full HD", "price": 79.39, "img": "https://via.placeholder.com/100?text=Webcam+Full+"}, {"title": "SSD Festplatte", "price": 36.47, "img": "https://via.placeholder.com/100?text=SSD+Festplat"}, {"title": "VR Brille", "price": 136.34, "img": "https://via.placeholder.com/100?text=VR+Brille"}, {"title": "Digitale Lupe", "price": 9.34, "img": "https://via.placeholder.com/100?text=Digitale+Lup"}, {"title": "GPS Tracker", "price": 31.43, "img": "https://via.placeholder.com/100?text=GPS+Tracker"}, {"title": "Kamera-Detektor", "price": 125.61, "img": "https://via.placeholder.com/100?text=Kamera-Detek"}, {"title": "RFID-Scanner", "price": 82.39, "img": "https://via.placeholder.com/100?text=RFID-Scanner"}, {"title": "Mini-Drucker", "price": 10.58, "img": "https://via.placeholder.com/100?text=Mini-Drucker"}, {"title": "Fingerabdruck-Schloss", "price": 55.33, "img": "https://via.placeholder.com/100?text=Fingerabdruc"}, {"title": "WiFi Kamera", "price": 2.51, "img": "https://via.placeholder.com/100?text=WiFi+Kamera"}, {"title": "Multimeter Digital", "price": 80.07, "img": "https://via.placeholder.com/100?text=Multimeter+D"}, {"title": "E-Book Reader", "price": 9.65, "img": "https://via.placeholder.com/100?text=E-Book+Reade"}, {"title": "Luftreiniger", "price": 1.29, "img": "https://via.placeholder.com/100?text=Luftreiniger"}, {"title": "Kompakter Ventilator", "price": 9.64, "img": "https://via.placeholder.com/100?text=Kompakter+Ve"}, {"title": "Controller PS4", "price": 139.0, "img": "https://via.placeholder.com/100?text=Controller+P"}, {"title": "Gaming Stuhl", "price": 138.18, "img": "https://via.placeholder.com/100?text=Gaming+Stuhl"}, {"title": "Cooling Pad", "price": 1.22, "img": "https://via.placeholder.com/100?text=Cooling+Pad"}, {"title": "Switch Pro Controller", "price": 130.43, "img": "https://via.placeholder.com/100?text=Switch+Pro+C"}, {"title": "Webcam 60fps", "price": 124.89, "img": "https://via.placeholder.com/100?text=Webcam+60fps"}, {"title": "RGB LED Strip", "price": 9.1, "img": "https://via.placeholder.com/100?text=RGB+LED+Stri"}, {"title": "Gaming Tischlampe", "price": 39.38, "img": "https://via.placeholder.com/100?text=Gaming+Tisch"}, {"title": "Stream Deck", "price": 119.75, "img": "https://via.placeholder.com/100?text=Stream+Deck"}, {"title": "Gamepad Mobile", "price": 130.67, "img": "https://via.placeholder.com/100?text=Gamepad+Mobi"}, {"title": "Mini Projektor", "price": 77.45, "img": "https://via.placeholder.com/100?text=Mini+Projekt"}, {"title": "Wireless Gaming Pad", "price": 9.16, "img": "https://via.placeholder.com/100?text=Wireless+Gam"}, {"title": "RGB Keycaps", "price": 5.91, "img": "https://via.placeholder.com/100?text=RGB+Keycaps"}, {"title": "Streaming-Mikrofon", "price": 4.77, "img": "https://via.placeholder.com/100?text=Streaming-Mi"}, {"title": "Switch Tasche", "price": 64.42, "img": "https://via.placeholder.com/100?text=Switch+Tasch"}];
    let index__sonstiges = 0;
    const step__sonstiges = 10;
    const container__sonstiges = document.getElementById("container__sonstiges");
    const btn__sonstiges = document.getElementById("btn__sonstiges");

    function render__sonstiges() {
      const next = container__sonstiges_products.slice(index__sonstiges, index__sonstiges + step__sonstiges);
      next.forEach(p => {
        const div = document.createElement("div");
        div.className = "product";
        
div.innerHTML = `
  <a href="produkt-${p.title.toLowerCase().replaceAll(' ', '-').replaceAll('ä','ae').replaceAll('ö','oe').replaceAll('ü','ue').replaceAll('ß','ss')}.html" style='text-decoration: none; color: inherit;'>
    <div class="product-inner">
      <img src="${p.img}" alt="${p.title}" />
      <div class="product-title">${p.title}</div>
      <div class="product-price">${p.price.toFixed(2)} €</div>
      <div class="product-desc">${p.description || ""}</div>
    </div>
  </a>
`;

        container__sonstiges.appendChild(div);
      });
      index__sonstiges += step__sonstiges;
      if (index__sonstiges >= container__sonstiges_products.length) {
        btn__sonstiges.style.display = "none";
      }
    }
    btn__sonstiges.addEventListener("click", render__sonstiges);
    render__sonstiges();
    

    const container__kopfhrer__musikboxen_products = [{"title": "Bluetooth Headset", "price": 13.44, "img": "https://via.placeholder.com/100?text=Bluetooth+He"}, {"title": "Bluetooth Lautsprecher", "price": 92.38, "img": "https://via.placeholder.com/100?text=Bluetooth+La"}, {"title": "Mini-Speaker", "price": 97.01, "img": "https://via.placeholder.com/100?text=Mini-Speaker"}, {"title": "Smart Speaker", "price": 105.71, "img": "https://via.placeholder.com/100?text=Smart+Speake"}, {"title": "Wasserdichter Speaker", "price": 9.96, "img": "https://via.placeholder.com/100?text=Wasserdichte"}, {"title": "Tragbare Box", "price": 5.3, "img": "https://via.placeholder.com/100?text=Tragbare+Box"}, {"title": "Lautsprecher mit LED", "price": 88.11, "img": "https://via.placeholder.com/100?text=Lautsprecher"}, {"title": "Retro Lautsprecher", "price": 40.44, "img": "https://via.placeholder.com/100?text=Retro+Lautsp"}, {"title": "NFC-Box", "price": 35.79, "img": "https://via.placeholder.com/100?text=NFC-Box"}, {"title": "Party-Lautsprecher", "price": 6.12, "img": "https://via.placeholder.com/100?text=Party-Lautsp"}, {"title": "Wireless Charging Speaker", "price": 19.36, "img": "https://via.placeholder.com/100?text=Wireless+Cha"}, {"title": "Karaoke Lautsprecher", "price": 8.77, "img": "https://via.placeholder.com/100?text=Karaoke+Laut"}, {"title": "Clip-Speaker", "price": 23.12, "img": "https://via.placeholder.com/100?text=Clip-Speaker"}, {"title": "Gaming Headset", "price": 27.71, "img": "https://via.placeholder.com/100?text=Gaming+Heads"}, {"title": "JBL Bluetooth Box", "price": 89.99, "img": "https://via.placeholder.com/100?text=JBL"}];
    let index__kopfhrer__musikboxen = 0;
    const step__kopfhrer__musikboxen = 10;
    const container__kopfhrer__musikboxen = document.getElementById("container__kopfhrer__musikboxen");
    const btn__kopfhrer__musikboxen = document.getElementById("btn__kopfhrer__musikboxen");

    function render__kopfhrer__musikboxen() {
      const next = container__kopfhrer__musikboxen_products.slice(index__kopfhrer__musikboxen, index__kopfhrer__musikboxen + step__kopfhrer__musikboxen);
      next.forEach(p => {
        const div = document.createElement("div");
        div.className = "product";
        
div.innerHTML = `
  <a href="produkt-${p.title.toLowerCase().replaceAll(' ', '-').replaceAll('ä','ae').replaceAll('ö','oe').replaceAll('ü','ue').replaceAll('ß','ss')}.html" style='text-decoration: none; color: inherit;'>
    <div class="product-inner">
      <img src="${p.img}" alt="${p.title}" />
      <div class="product-title">${p.title}</div>
      <div class="product-price">${p.price.toFixed(2)} €</div>
      <div class="product-desc">${p.description || ""}</div>
    </div>
  </a>
`;

        container__kopfhrer__musikboxen.appendChild(div);
      });
      index__kopfhrer__musikboxen += step__kopfhrer__musikboxen;
      if (index__kopfhrer__musikboxen >= container__kopfhrer__musikboxen_products.length) {
        btn__kopfhrer__musikboxen.style.display = "none";
      }
    }
    btn__kopfhrer__musikboxen.addEventListener("click", render__kopfhrer__musikboxen);
    render__kopfhrer__musikboxen();
    
</script>

<script>

function addToCart(name, price) { alert(`${name} zum Warenkorb hinzugefügt!`); }

    const container__handy_und_zubehr_products = [{"title": "Ladekabel", "price": 15.14, "img": "https://via.placeholder.com/100?text=Ladekabel"}, {"title": "Hülle iPhone", "price": 2.25, "img": "https://via.placeholder.com/100?text=Hülle+iPhone"}, {"title": "Powerbank", "price": 106.41, "img": "https://via.placeholder.com/100?text=Powerbank"}, {"title": "Kfz-Ladegerät", "price": 6.27, "img": "https://via.placeholder.com/100?text=Kfz-Ladegerä"}, {"title": "Displayschutzfolie", "price": 106.38, "img": "https://via.placeholder.com/100?text=Displayschut"}, {"title": "Handyhülle transparent", "price": 46.08, "img": "https://via.placeholder.com/100?text=Handyhülle+t"}, {"title": "Handy-Kühlpad", "price": 64.13, "img": "https://via.placeholder.com/100?text=Handy-Kühlpa"}, {"title": "Lade-Dock", "price": 5.62, "img": "https://via.placeholder.com/100?text=Lade-Dock"}, {"title": "Handy-Armband", "price": 11.17, "img": "https://via.placeholder.com/100?text=Handy-Armban"}, {"title": "2-in-1 Kabel", "price": 5.33, "img": "https://via.placeholder.com/100?text=2-in-1+Kabel"}, {"title": "USB-Ladegerät", "price": 142.38, "img": "https://via.placeholder.com/100?text=USB-Ladegerä"}, {"title": "Handyhalterung", "price": 100.16, "img": "https://via.placeholder.com/100?text=Handyhalteru"}, {"title": "Ladekabel Auto", "price": 6.74, "img": "https://via.placeholder.com/100?text=Ladekabel+Au"}, {"title": "Handyhalterung fürs Auto", "price": 29.99, "img": "https://via.placeholder.com/100?text=Auto"}, {"title": "Powerbank 20000mAh", "price": 39.99, "img": "https://via.placeholder.com/100?text=Powerbank"}];
    let index__handy_und_zubehr = 0;
    const step__handy_und_zubehr = 10;
    const container__handy_und_zubehr = document.getElementById("container__handy_und_zubehr");
    const btn__handy_und_zubehr = document.getElementById("btn__handy_und_zubehr");

    function render__handy_und_zubehr() {
      const next = container__handy_und_zubehr_products.slice(index__handy_und_zubehr, index__handy_und_zubehr + step__handy_und_zubehr);
      next.forEach(p => {
        const div = document.createElement("div");
        div.className = "product";
        
div.innerHTML = `
  <a href="produkt-${p.title.toLowerCase().replaceAll(' ', '-').replaceAll('ä','ae').replaceAll('ö','oe').replaceAll('ü','ue').replaceAll('ß','ss')}.html" style='text-decoration: none; color: inherit;'>
    <div class="product-inner">
      <img src="${p.img}" alt="${p.title}" />
      <div class="product-title">${p.title}</div>
      <div class="product-price">${p.price.toFixed(2)} €</div>
      <div class="product-desc">${p.description || ""}</div>
    </div>
  </a>
`;

        container__handy_und_zubehr.appendChild(div);
      });
      index__handy_und_zubehr += step__handy_und_zubehr;
      if (index__handy_und_zubehr >= container__handy_und_zubehr_products.length) {
        btn__handy_und_zubehr.style.display = "none";
      }
    }
    btn__handy_und_zubehr.addEventListener("click", render__handy_und_zubehr);
    render__handy_und_zubehr();
    

    const container__pc_zubehr_products = [{"title": "USB-C Adapter", "price": 113.39, "img": "https://via.placeholder.com/100?text=USB-C+Adapte"}, {"title": "Tablet-Ständer", "price": 21.6, "img": "https://via.placeholder.com/100?text=Tablet-Ständ"}, {"title": "USB-Stick Musik", "price": 50.92, "img": "https://via.placeholder.com/100?text=USB-Stick+Mu"}, {"title": "USB Speaker", "price": 132.54, "img": "https://via.placeholder.com/100?text=USB+Speaker"}, {"title": "PC Lautsprecher", "price": 107.49, "img": "https://via.placeholder.com/100?text=PC+Lautsprec"}, {"title": "USB LED Lampe", "price": 21.27, "img": "https://via.placeholder.com/100?text=USB+LED+Lamp"}, {"title": "LED Monitor Light", "price": 5.05, "img": "https://via.placeholder.com/100?text=LED+Monitor+"}, {"title": "USB Nachtlicht", "price": 56.69, "img": "https://via.placeholder.com/100?text=USB+Nachtlic"}, {"title": "LED PC Leiste", "price": 70.54, "img": "https://via.placeholder.com/100?text=LED+PC+Leist"}, {"title": "USB Lichtband", "price": 35.84, "img": "https://via.placeholder.com/100?text=USB+Lichtban"}, {"title": "Mini-PC", "price": 64.64, "img": "https://via.placeholder.com/100?text=Mini-PC"}, {"title": "Bluetooth Tastatur", "price": 113.92, "img": "https://via.placeholder.com/100?text=Bluetooth+Ta"}, {"title": "USB-Hub", "price": 100.38, "img": "https://via.placeholder.com/100?text=USB-Hub"}, {"title": "USB Mikroskop", "price": 131.44, "img": "https://via.placeholder.com/100?text=USB+Mikrosko"}, {"title": "Maus mit Daumenrad", "price": 2.59, "img": "https://via.placeholder.com/100?text=Maus+mit+Dau"}, {"title": "USB Heizkissen", "price": 114.38, "img": "https://via.placeholder.com/100?text=USB+Heizkiss"}, {"title": "Gaming Maus", "price": 50.65, "img": "https://via.placeholder.com/100?text=Gaming+Maus"}, {"title": "RGB Mauspad", "price": 77.55, "img": "https://via.placeholder.com/100?text=RGB+Mauspad"}, {"title": "Mechanische Tastatur", "price": 142.83, "img": "https://via.placeholder.com/100?text=Mechanische+"}, {"title": "Maus Bungee", "price": 53.71, "img": "https://via.placeholder.com/100?text=Maus+Bungee"}, {"title": "Headset-Ständer", "price": 9.43, "img": "https://via.placeholder.com/100?text=Headset-Stän"}, {"title": "Joystick PC", "price": 4.74, "img": "https://via.placeholder.com/100?text=Joystick+PC"}, {"title": "USB Soundkarte", "price": 36.08, "img": "https://via.placeholder.com/100?text=USB+Soundkar"}, {"title": "Ergonomische Maus", "price": 141.43, "img": "https://via.placeholder.com/100?text=Ergonomische"}, {"title": "Gaming Maus", "price": 49.99, "img": "https://via.placeholder.com/100?text=Gaming"}];
    let index__pc_zubehr = 0;
    const step__pc_zubehr = 10;
    const container__pc_zubehr = document.getElementById("container__pc_zubehr");
    const btn__pc_zubehr = document.getElementById("btn__pc_zubehr");

    function render__pc_zubehr() {
      const next = container__pc_zubehr_products.slice(index__pc_zubehr, index__pc_zubehr + step__pc_zubehr);
      next.forEach(p => {
        const div = document.createElement("div");
        div.className = "product";
        
div.innerHTML = `
  <a href="produkt-${p.title.toLowerCase().replaceAll(' ', '-').replaceAll('ä','ae').replaceAll('ö','oe').replaceAll('ü','ue').replaceAll('ß','ss')}.html" style='text-decoration: none; color: inherit;'>
    <div class="product-inner">
      <img src="${p.img}" alt="${p.title}" />
      <div class="product-title">${p.title}</div>
      <div class="product-price">${p.price.toFixed(2)} €</div>
      <div class="product-desc">${p.description || ""}</div>
    </div>
  </a>
`;

        container__pc_zubehr.appendChild(div);
      });
      index__pc_zubehr += step__pc_zubehr;
      if (index__pc_zubehr >= container__pc_zubehr_products.length) {
        btn__pc_zubehr.style.display = "none";
      }
    }
    btn__pc_zubehr.addEventListener("click", render__pc_zubehr);
    render__pc_zubehr();
    

    const container__auto_gadgets_products = [{"title": "MagSafe Halterung", "price": 2.65, "img": "https://via.placeholder.com/100?text=MagSafe+Halt"}, {"title": "CarPlay Dongle", "price": 6.16, "img": "https://via.placeholder.com/100?text=CarPlay+Dong"}, {"title": "Smartphone-Halterung Fahrrad", "price": 21.04, "img": "https://via.placeholder.com/100?text=Smartphone-H"}, {"title": "Auto-Organizer", "price": 141.16, "img": "https://via.placeholder.com/100?text=Auto-Organiz"}, {"title": "Auto-Luftreiniger", "price": 83.5, "img": "https://via.placeholder.com/100?text=Auto-Luftrei"}, {"title": "Auto-Sitzhaken", "price": 41.39, "img": "https://via.placeholder.com/100?text=Auto-Sitzhak"}, {"title": "Getränkehalter", "price": 82.6, "img": "https://via.placeholder.com/100?text=Getränkehalt"}, {"title": "Auto-Duftspender", "price": 55.63, "img": "https://via.placeholder.com/100?text=Auto-Duftspe"}, {"title": "Auto-Staubsauger", "price": 32.96, "img": "https://via.placeholder.com/100?text=Auto-Staubsa"}, {"title": "Tablet-Halter Kopfstütze", "price": 3.31, "img": "https://via.placeholder.com/100?text=Tablet-Halte"}, {"title": "Luftbefeuchter Auto", "price": 40.06, "img": "https://via.placeholder.com/100?text=Luftbefeucht"}, {"title": "Lautsprecher Halterung", "price": 93.2, "img": "https://via.placeholder.com/100?text=Lautsprecher"}, {"title": "Auto-Bluetooth Speaker", "price": 1.52, "img": "https://via.placeholder.com/100?text=Auto-Bluetoo"}, {"title": "Tischhalter Monitor", "price": 5.84, "img": "https://via.placeholder.com/100?text=Tischhalter+"}, {"title": "Capture Card", "price": 14.06, "img": "https://via.placeholder.com/100?text=Capture+Card"}];
    let index__auto_gadgets = 0;
    const step__auto_gadgets = 10;
    const container__auto_gadgets = document.getElementById("container__auto_gadgets");
    const btn__auto_gadgets = document.getElementById("btn__auto_gadgets");

    function render__auto_gadgets() {
      const next = container__auto_gadgets_products.slice(index__auto_gadgets, index__auto_gadgets + step__auto_gadgets);
      next.forEach(p => {
        const div = document.createElement("div");
        div.className = "product";
        
div.innerHTML = `
  <a href="produkt-${p.title.toLowerCase().replaceAll(' ', '-').replaceAll('ä','ae').replaceAll('ö','oe').replaceAll('ü','ue').replaceAll('ß','ss')}.html" style='text-decoration: none; color: inherit;'>
    <div class="product-inner">
      <img src="${p.img}" alt="${p.title}" />
      <div class="product-title">${p.title}</div>
      <div class="product-price">${p.price.toFixed(2)} €</div>
      <div class="product-desc">${p.description || ""}</div>
    </div>
  </a>
`;

        container__auto_gadgets.appendChild(div);
      });
      index__auto_gadgets += step__auto_gadgets;
      if (index__auto_gadgets >= container__auto_gadgets_products.length) {
        btn__auto_gadgets.style.display = "none";
      }
    }
    btn__auto_gadgets.addEventListener("click", render__auto_gadgets);
    render__auto_gadgets();
    

    const container__sonstiges_products = [{"title": "Wireless Charger", "price": 100.75, "img": "https://via.placeholder.com/100?text=Wireless+Cha"}, {"title": "PopSocket", "price": 4.61, "img": "https://via.placeholder.com/100?text=PopSocket"}, {"title": "Selfie Stick", "price": 7.32, "img": "https://via.placeholder.com/100?text=Selfie+Stick"}, {"title": "Mini-Stativ", "price": 19.64, "img": "https://via.placeholder.com/100?text=Mini-Stativ"}, {"title": "Reinigungstuch", "price": 47.7, "img": "https://via.placeholder.com/100?text=Reinigungstu"}, {"title": "Gaming-Grip", "price": 47.5, "img": "https://via.placeholder.com/100?text=Gaming-Grip"}, {"title": "iPad Pencil Ersatz", "price": 125.97, "img": "https://via.placeholder.com/100?text=iPad+Pencil+"}, {"title": "Anti-Rutsch-Pad", "price": 65.39, "img": "https://via.placeholder.com/100?text=Anti-Rutsch-"}, {"title": "Multifunktions-Clip", "price": 21.63, "img": "https://via.placeholder.com/100?text=Multifunktio"}, {"title": "LED Innenraum", "price": 5.32, "img": "https://via.placeholder.com/100?text=LED+Innenrau"}, {"title": "Reifen-Luftdruckprüfer", "price": 22.35, "img": "https://via.placeholder.com/100?text=Reifen-Luftd"}, {"title": "Kofferraumnetz", "price": 79.12, "img": "https://via.placeholder.com/100?text=Kofferraumne"}, {"title": "Parkhilfe", "price": 50.73, "img": "https://via.placeholder.com/100?text=Parkhilfe"}, {"title": "Bluetooth FM-Transmitter", "price": 27.9, "img": "https://via.placeholder.com/100?text=Bluetooth+FM"}, {"title": "Dashcam", "price": 41.16, "img": "https://via.placeholder.com/100?text=Dashcam"}, {"title": "Sitzkissen", "price": 85.79, "img": "https://via.placeholder.com/100?text=Sitzkissen"}, {"title": "Scheibenabdeckung", "price": 8.73, "img": "https://via.placeholder.com/100?text=Scheibenabde"}, {"title": "Schmutzmatte", "price": 97.86, "img": "https://via.placeholder.com/100?text=Schmutzmatte"}, {"title": "Notfallhammer", "price": 7.07, "img": "https://via.placeholder.com/100?text=Notfallhamme"}, {"title": "Smart Key Cover", "price": 3.45, "img": "https://via.placeholder.com/100?text=Smart+Key+Co"}, {"title": "Gurtpolster", "price": 3.35, "img": "https://via.placeholder.com/100?text=Gurtpolster"}, {"title": "LED Projektor Logo", "price": 5.51, "img": "https://via.placeholder.com/100?text=LED+Projekto"}, {"title": "JBL GO", "price": 0.8, "img": "https://via.placeholder.com/100?text=JBL+GO"}, {"title": "Bose SoundLink", "price": 21.22, "img": "https://via.placeholder.com/100?text=Bose+SoundLi"}, {"title": "Anker SoundCore", "price": 19.31, "img": "https://via.placeholder.com/100?text=Anker+SoundC"}, {"title": "Marshall Emberton", "price": 7.97, "img": "https://via.placeholder.com/100?text=Marshall+Emb"}, {"title": "Sony XB13", "price": 61.38, "img": "https://via.placeholder.com/100?text=Sony+XB13"}, {"title": "Echo Dot", "price": 98.83, "img": "https://via.placeholder.com/100?text=Echo+Dot"}, {"title": "Subwoofer", "price": 19.98, "img": "https://via.placeholder.com/100?text=Subwoofer"}, {"title": "Soundbar", "price": 18.21, "img": "https://via.placeholder.com/100?text=Soundbar"}, {"title": "Radio mit BT", "price": 0.98, "img": "https://via.placeholder.com/100?text=Radio+mit+BT"}, {"title": "LED Strip 5m", "price": 53.45, "img": "https://via.placeholder.com/100?text=LED+Strip+5m"}, {"title": "Bewegungssensor Licht", "price": 5.38, "img": "https://via.placeholder.com/100?text=Bewegungssen"}, {"title": "RGB Tischlampe", "price": 26.38, "img": "https://via.placeholder.com/100?text=RGB+Tischlam"}, {"title": "LED Eckenleiste", "price": 7.35, "img": "https://via.placeholder.com/100?text=LED+Eckenlei"}, {"title": "Smart Glühbirne", "price": 98.08, "img": "https://via.placeholder.com/100?text=Smart+Glühbi"}, {"title": "LED Duschkopf", "price": 44.92, "img": "https://via.placeholder.com/100?text=LED+Duschkop"}, {"title": "Licht mit Fernbedienung", "price": 111.77, "img": "https://via.placeholder.com/100?text=Licht+mit+Fe"}, {"title": "Sonnenaufgangswecker", "price": 22.0, "img": "https://via.placeholder.com/100?text=Sonnenaufgan"}, {"title": "Laserprojektor", "price": 1.62, "img": "https://via.placeholder.com/100?text=Laserprojekt"}, {"title": "Lichterkette", "price": 9.8, "img": "https://via.placeholder.com/100?text=Lichterkette"}, {"title": "Touchlampe", "price": 4.76, "img": "https://via.placeholder.com/100?text=Touchlampe"}, {"title": "Schreibtisch-LED", "price": 92.88, "img": "https://via.placeholder.com/100?text=Schreibtisch"}, {"title": "LED Ringlicht", "price": 8.85, "img": "https://via.placeholder.com/100?text=LED+Ringlich"}, {"title": "Gaming LED Pads", "price": 81.79, "img": "https://via.placeholder.com/100?text=Gaming+LED+P"}, {"title": "WLAN Steckdose", "price": 146.6, "img": "https://via.placeholder.com/100?text=WLAN+Steckdo"}, {"title": "Timer Plug", "price": 2.35, "img": "https://via.placeholder.com/100?text=Timer+Plug"}, {"title": "Smart Plug", "price": 31.7, "img": "https://via.placeholder.com/100?text=Smart+Plug"}, {"title": "Stimmungslicht", "price": 50.02, "img": "https://via.placeholder.com/100?text=Stimmungslic"}, {"title": "Licht Panel", "price": 102.24, "img": "https://via.placeholder.com/100?text=Licht+Panel"}, {"title": "RGB Wanddeko", "price": 67.18, "img": "https://via.placeholder.com/100?text=RGB+Wanddeko"}, {"title": "Mini-Beamer", "price": 52.91, "img": "https://via.placeholder.com/100?text=Mini-Beamer"}, {"title": "Smartwatch", "price": 5.27, "img": "https://via.placeholder.com/100?text=Smartwatch"}, {"title": "TV Stick", "price": 17.22, "img": "https://via.placeholder.com/100?text=TV+Stick"}, {"title": "WiFi Repeater", "price": 5.48, "img": "https://via.placeholder.com/100?text=WiFi+Repeate"}, {"title": "Action Cam", "price": 145.88, "img": "https://via.placeholder.com/100?text=Action+Cam"}, {"title": "Webcam Full HD", "price": 79.39, "img": "https://via.placeholder.com/100?text=Webcam+Full+"}, {"title": "SSD Festplatte", "price": 36.47, "img": "https://via.placeholder.com/100?text=SSD+Festplat"}, {"title": "VR Brille", "price": 136.34, "img": "https://via.placeholder.com/100?text=VR+Brille"}, {"title": "Digitale Lupe", "price": 9.34, "img": "https://via.placeholder.com/100?text=Digitale+Lup"}, {"title": "GPS Tracker", "price": 31.43, "img": "https://via.placeholder.com/100?text=GPS+Tracker"}, {"title": "Kamera-Detektor", "price": 125.61, "img": "https://via.placeholder.com/100?text=Kamera-Detek"}, {"title": "RFID-Scanner", "price": 82.39, "img": "https://via.placeholder.com/100?text=RFID-Scanner"}, {"title": "Mini-Drucker", "price": 10.58, "img": "https://via.placeholder.com/100?text=Mini-Drucker"}, {"title": "Fingerabdruck-Schloss", "price": 55.33, "img": "https://via.placeholder.com/100?text=Fingerabdruc"}, {"title": "WiFi Kamera", "price": 2.51, "img": "https://via.placeholder.com/100?text=WiFi+Kamera"}, {"title": "Multimeter Digital", "price": 80.07, "img": "https://via.placeholder.com/100?text=Multimeter+D"}, {"title": "E-Book Reader", "price": 9.65, "img": "https://via.placeholder.com/100?text=E-Book+Reade"}, {"title": "Luftreiniger", "price": 1.29, "img": "https://via.placeholder.com/100?text=Luftreiniger"}, {"title": "Kompakter Ventilator", "price": 9.64, "img": "https://via.placeholder.com/100?text=Kompakter+Ve"}, {"title": "Controller PS4", "price": 139.0, "img": "https://via.placeholder.com/100?text=Controller+P"}, {"title": "Gaming Stuhl", "price": 138.18, "img": "https://via.placeholder.com/100?text=Gaming+Stuhl"}, {"title": "Cooling Pad", "price": 1.22, "img": "https://via.placeholder.com/100?text=Cooling+Pad"}, {"title": "Switch Pro Controller", "price": 130.43, "img": "https://via.placeholder.com/100?text=Switch+Pro+C"}, {"title": "Webcam 60fps", "price": 124.89, "img": "https://via.placeholder.com/100?text=Webcam+60fps"}, {"title": "RGB LED Strip", "price": 9.1, "img": "https://via.placeholder.com/100?text=RGB+LED+Stri"}, {"title": "Gaming Tischlampe", "price": 39.38, "img": "https://via.placeholder.com/100?text=Gaming+Tisch"}, {"title": "Stream Deck", "price": 119.75, "img": "https://via.placeholder.com/100?text=Stream+Deck"}, {"title": "Gamepad Mobile", "price": 130.67, "img": "https://via.placeholder.com/100?text=Gamepad+Mobi"}, {"title": "Mini Projektor", "price": 77.45, "img": "https://via.placeholder.com/100?text=Mini+Projekt"}, {"title": "Wireless Gaming Pad", "price": 9.16, "img": "https://via.placeholder.com/100?text=Wireless+Gam"}, {"title": "RGB Keycaps", "price": 5.91, "img": "https://via.placeholder.com/100?text=RGB+Keycaps"}, {"title": "Streaming-Mikrofon", "price": 4.77, "img": "https://via.placeholder.com/100?text=Streaming-Mi"}, {"title": "Switch Tasche", "price": 64.42, "img": "https://via.placeholder.com/100?text=Switch+Tasch"}];
    let index__sonstiges = 0;
    const step__sonstiges = 10;
    const container__sonstiges = document.getElementById("container__sonstiges");
    const btn__sonstiges = document.getElementById("btn__sonstiges");

    function render__sonstiges() {
      const next = container__sonstiges_products.slice(index__sonstiges, index__sonstiges + step__sonstiges);
      next.forEach(p => {
        const div = document.createElement("div");
        div.className = "product";
        
div.innerHTML = `
  <a href="produkt-${p.title.toLowerCase().replaceAll(' ', '-').replaceAll('ä','ae').replaceAll('ö','oe').replaceAll('ü','ue').replaceAll('ß','ss')}.html" style='text-decoration: none; color: inherit;'>
    <div class="product-inner">
      <img src="${p.img}" alt="${p.title}" />
      <div class="product-title">${p.title}</div>
      <div class="product-price">${p.price.toFixed(2)} €</div>
      <div class="product-desc">${p.description || ""}</div>
    </div>
  </a>
`;

        container__sonstiges.appendChild(div);
      });
      index__sonstiges += step__sonstiges;
      if (index__sonstiges >= container__sonstiges_products.length) {
        btn__sonstiges.style.display = "none";
      }
    }
    btn__sonstiges.addEventListener("click", render__sonstiges);
    render__sonstiges();
    

    const container__kopfhrer__musikboxen_products = [{"title": "Bluetooth Headset", "price": 13.44, "img": "https://via.placeholder.com/100?text=Bluetooth+He"}, {"title": "Bluetooth Lautsprecher", "price": 92.38, "img": "https://via.placeholder.com/100?text=Bluetooth+La"}, {"title": "Mini-Speaker", "price": 97.01, "img": "https://via.placeholder.com/100?text=Mini-Speaker"}, {"title": "Smart Speaker", "price": 105.71, "img": "https://via.placeholder.com/100?text=Smart+Speake"}, {"title": "Wasserdichter Speaker", "price": 9.96, "img": "https://via.placeholder.com/100?text=Wasserdichte"}, {"title": "Tragbare Box", "price": 5.3, "img": "https://via.placeholder.com/100?text=Tragbare+Box"}, {"title": "Lautsprecher mit LED", "price": 88.11, "img": "https://via.placeholder.com/100?text=Lautsprecher"}, {"title": "Retro Lautsprecher", "price": 40.44, "img": "https://via.placeholder.com/100?text=Retro+Lautsp"}, {"title": "NFC-Box", "price": 35.79, "img": "https://via.placeholder.com/100?text=NFC-Box"}, {"title": "Party-Lautsprecher", "price": 6.12, "img": "https://via.placeholder.com/100?text=Party-Lautsp"}, {"title": "Wireless Charging Speaker", "price": 19.36, "img": "https://via.placeholder.com/100?text=Wireless+Cha"}, {"title": "Karaoke Lautsprecher", "price": 8.77, "img": "https://via.placeholder.com/100?text=Karaoke+Laut"}, {"title": "Clip-Speaker", "price": 23.12, "img": "https://via.placeholder.com/100?text=Clip-Speaker"}, {"title": "Gaming Headset", "price": 27.71, "img": "https://via.placeholder.com/100?text=Gaming+Heads"}, {"title": "JBL Bluetooth Box", "price": 89.99, "img": "https://via.placeholder.com/100?text=JBL"}];
    let index__kopfhrer__musikboxen = 0;
    const step__kopfhrer__musikboxen = 10;
    const container__kopfhrer__musikboxen = document.getElementById("container__kopfhrer__musikboxen");
    const btn__kopfhrer__musikboxen = document.getElementById("btn__kopfhrer__musikboxen");

    function render__kopfhrer__musikboxen() {
      const next = container__kopfhrer__musikboxen_products.slice(index__kopfhrer__musikboxen, index__kopfhrer__musikboxen + step__kopfhrer__musikboxen);
      next.forEach(p => {
        const div = document.createElement("div");
        div.className = "product";
        
div.innerHTML = `
  <a href="produkt-${p.title.toLowerCase().replaceAll(' ', '-').replaceAll('ä','ae').replaceAll('ö','oe').replaceAll('ü','ue').replaceAll('ß','ss')}.html" style='text-decoration: none; color: inherit;'>
    <div class="product-inner">
      <img src="${p.img}" alt="${p.title}" />
      <div class="product-title">${p.title}</div>
      <div class="product-price">${p.price.toFixed(2)} €</div>
      <div class="product-desc">${p.description || ""}</div>
    </div>
  </a>
`;

        container__kopfhrer__musikboxen.appendChild(div);
      });
      index__kopfhrer__musikboxen += step__kopfhrer__musikboxen;
      if (index__kopfhrer__musikboxen >= container__kopfhrer__musikboxen_products.length) {
        btn__kopfhrer__musikboxen.style.display = "none";
      }
    }
    btn__kopfhrer__musikboxen.addEventListener("click", render__kopfhrer__musikboxen);
    render__kopfhrer__musikboxen();
    



const container___handy_und_zubehr_products = [{"title": "USB‑Kabel (USB‑C)", "img": "https://cdn.pixabay.com/photo/2016/12/06/18/27/usb-1881808_1280.jpg", "price": 5.99, "description": "Stabiles USB‑C Kabel für schnelles Laden…", "fullDescription": "USB‑A auf USB‑C Kabel mit 480 Mbit/s Datenübertragung, 3A Schnellladen, ideal für Smartphones, Tablets und mehr."}, {"title": "Powerbank 10.000 mAh", "img": "https://cdn.pixabay.com/photo/2018/03/10/18/27/power-bank-3212455_1280.jpg", "price": 21.0, "description": "Kompakte Powerbank für unterwegs…", "fullDescription": "10.000 mAh Powerbank mit LED-Anzeige, zwei USB-Ausgängen & Schnellladefunktion für mobile Geräte."}];
const container___pc_zubehr_products = [{"title": "Bluetooth Maus", "img": "https://cdn.pixabay.com/photo/2016/04/19/14/00/mouse-1331691_1280.jpg", "price": 12.9, "description": "Kabellose Maus für Büro & Freizeit…", "fullDescription": "Ergonomische Bluetooth-Maus mit hoher Präzision, 3 DPI-Stufen und leiser Klicktechnik."}];
const container___auto_gadgets_products = [{"title": "KFZ-Handyhalterung", "img": "https://cdn.pixabay.com/photo/2021/08/15/15/50/car-6547921_1280.jpg", "price": 8.49, "description": "Universalhalterung für dein Smartphone…", "fullDescription": "360° drehbare, rutschfeste Handyhalterung für Auto-Lüftungsschlitze. Passt für fast alle Handymodelle."}];

    let index__handy_und_zubehr = 0;
    const step__handy_und_zubehr = 10;
    const container__handy_und_zubehr = document.getElementById("container___handy_und_zubehr");
    const btn__handy_und_zubehr = document.getElementById("btn___handy_und_zubehr");

    function render___handy_und_zubehr() {
      const next = container___handy_und_zubehr_products.slice(index__handy_und_zubehr, index__handy_und_zubehr + step__handy_und_zubehr);
      next.forEach(p => {
        const div = document.createElement("div");
        div.className = "product";
        
div.innerHTML = `
  <a href="produkt-${p.title.toLowerCase().replaceAll(' ', '-').replaceAll('ä','ae').replaceAll('ö','oe').replaceAll('ü','ue').replaceAll('ß','ss')}.html" style='text-decoration: none; color: inherit;'>
    <div class="product-inner">
      <img src="${p.img}" alt="${p.title}" />
      <div class="product-title">${p.title}</div>
      <div class="product-price">${p.price.toFixed(2)} €</div>
      <div class="product-desc">${p.description || ""}</div>
    </div>
  </a>
`;

        container__handy_und_zubehr.appendChild(div);
      });
      index__handy_und_zubehr += step__handy_und_zubehr;
      if (index__handy_und_zubehr >= container___handy_und_zubehr_products.length) {
        btn__handy_und_zubehr.style.display = "none";
      }
    }

    btn__handy_und_zubehr.addEventListener("click", render___handy_und_zubehr);
    render___handy_und_zubehr();
    

    let index__pc_zubehr = 0;
    const step__pc_zubehr = 10;
    const container__pc_zubehr = document.getElementById("container___pc_zubehr");
    const btn__pc_zubehr = document.getElementById("btn___pc_zubehr");

    function render___pc_zubehr() {
      const next = container___pc_zubehr_products.slice(index__pc_zubehr, index__pc_zubehr + step__pc_zubehr);
      next.forEach(p => {
        const div = document.createElement("div");
        div.className = "product";
        
div.innerHTML = `
  <a href="produkt-${p.title.toLowerCase().replaceAll(' ', '-').replaceAll('ä','ae').replaceAll('ö','oe').replaceAll('ü','ue').replaceAll('ß','ss')}.html" style='text-decoration: none; color: inherit;'>
    <div class="product-inner">
      <img src="${p.img}" alt="${p.title}" />
      <div class="product-title">${p.title}</div>
      <div class="product-price">${p.price.toFixed(2)} €</div>
      <div class="product-desc">${p.description || ""}</div>
    </div>
  </a>
`;

        container__pc_zubehr.appendChild(div);
      });
      index__pc_zubehr += step__pc_zubehr;
      if (index__pc_zubehr >= container___pc_zubehr_products.length) {
        btn__pc_zubehr.style.display = "none";
      }
    }

    btn__pc_zubehr.addEventListener("click", render___pc_zubehr);
    render___pc_zubehr();
    

    let index__auto_gadgets = 0;
    const step__auto_gadgets = 10;
    const container__auto_gadgets = document.getElementById("container___auto_gadgets");
    const btn__auto_gadgets = document.getElementById("btn___auto_gadgets");

    function render___auto_gadgets() {
      const next = container___auto_gadgets_products.slice(index__auto_gadgets, index__auto_gadgets + step__auto_gadgets);
      next.forEach(p => {
        const div = document.createElement("div");
        div.className = "product";
        
div.innerHTML = `
  <a href="produkt-${p.title.toLowerCase().replaceAll(' ', '-').replaceAll('ä','ae').replaceAll('ö','oe').replaceAll('ü','ue').replaceAll('ß','ss')}.html" style='text-decoration: none; color: inherit;'>
    <div class="product-inner">
      <img src="${p.img}" alt="${p.title}" />
      <div class="product-title">${p.title}</div>
      <div class="product-price">${p.price.toFixed(2)} €</div>
      <div class="product-desc">${p.description || ""}</div>
    </div>
  </a>
`;

        container__auto_gadgets.appendChild(div);
      });
      index__auto_gadgets += step__auto_gadgets;
      if (index__auto_gadgets >= container___auto_gadgets_products.length) {
        btn__auto_gadgets.style.display = "none";
      }
    }

    btn__auto_gadgets.addEventListener("click", render___auto_gadgets);
    render___auto_gadgets();
    
</script>

  <div id="modal" class="modal" style="display:none;">
    <div class="modal-content">
      <span class="close" onclick="closeModal()">&times;</span>
      <h2 id="modal-title"></h2>
      <img id="modal-img" src="" alt="" />
      <p id="modal-description"></p>
      <div id="modal-price"></div>
      <button class="btn" onclick="confirmAddToCart()">Jetzt kaufen</button>
    </div>
  </div>
  <div id="cart-toast"></div>
  <div id="cart-preview" class="cart-preview" style="display: none;"></div>


<script>
let cart = JSON.parse(localStorage.getItem("fitgear_cart") || "[]");
let selectedProduct = null;

function openModal(title, img, price, description) {
  selectedProduct = { title, img, price, description };
  document.getElementById("modal-title").innerText = title;
  document.getElementById("modal-img").src = img;
  document.getElementById("modal-description").innerText = description;
  document.getElementById("modal-price").innerText = price.toFixed(2) + " €";
  document.getElementById("modal").style.display = "flex";
}
function closeModal() {
  document.getElementById("modal").style.display = "none";
}
function confirmAddToCart() {
  addToCart(selectedProduct.title, selectedProduct.price);
  closeModal();
}
function addToCart(title, price) {
  const existing = cart.find(p => p.title === title);
  if (existing) {
    existing.qty += 1;
  } else {
    cart.push({ title, price, qty: 1 });
  }
  localStorage.setItem("fitgear_cart", JSON.stringify(cart));
  updateCartCount();
  showToast(title + " wurde dem Warenkorb hinzugefügt");
}
function updateCartCount() {
  const count = cart.reduce((sum, p) => sum + p.qty, 0);
  const badge = document.getElementById("cart-count");
  if (badge) badge.innerText = count;
}
function showToast(message) {
  const toast = document.getElementById("cart-toast");
  toast.innerText = message;
  toast.style.display = "block";
  setTimeout(() => toast.style.display = "none", 3000);
}
function toggleCartPreview() {
  const preview = document.getElementById("cart-preview");
  preview.innerHTML = "";
  cart.forEach(p => {
    preview.innerHTML += `<div>${p.title} × ${p.qty} – ${(p.qty * p.price).toFixed(2)} €</div>`;
  });
  preview.style.display = preview.style.display === "block" ? "none" : "block";
}
window.onload = updateCartCount;
</script>

</body></html>
</script>

  <div id="modal" class="modal" style="display:none;">
    <div class="modal-content">
      <span class="close" onclick="closeModal()">&times;</span>
      <h2 id="modal-title"></h2>
      <img id="modal-img" src="" alt="" />
      <p id="modal-description"></p>
      <div id="modal-price"></div>
      <button class="btn" onclick="confirmAddToCart()">Jetzt kaufen</button>
    </div>
  </div>
  <div id="cart-toast"></div>
  <div id="cart-preview" class="cart-preview" style="display: none;"></div>


<script>
let cart = JSON.parse(localStorage.getItem("fitgear_cart") || "[]");
let selectedProduct = null;

function openModal(title, img, price, description) {
  selectedProduct = { title, img, price, description };
  document.getElementById("modal-title").innerText = title;
  document.getElementById("modal-img").src = img;
  document.getElementById("modal-description").innerText = description;
  document.getElementById("modal-price").innerText = price.toFixed(2) + " €";
  document.getElementById("modal").style.display = "flex";
}
function closeModal() {
  document.getElementById("modal").style.display = "none";
}
function confirmAddToCart() {
  addToCart(selectedProduct.title, selectedProduct.price);
  closeModal();
}
function addToCart(title, price) {
  const existing = cart.find(p => p.title === title);
  if (existing) {
    existing.qty += 1;
  } else {
    cart.push({ title, price, qty: 1 });
  }
  localStorage.setItem("fitgear_cart", JSON.stringify(cart));
  updateCartCount();
  showToast(title + " wurde dem Warenkorb hinzugefügt");
}
function updateCartCount() {
  const count = cart.reduce((sum, p) => sum + p.qty, 0);
  const badge = document.getElementById("cart-count");
  if (badge) badge.innerText = count;
}
function showToast(message) {
  const toast = document.getElementById("cart-toast");
  toast.innerText = message;
  toast.style.display = "block";
  setTimeout(() => toast.style.display = "none", 3000);
}
function toggleCartPreview() {
  const preview = document.getElementById("cart-preview");
  preview.innerHTML = "";
  cart.forEach(p => {
    preview.innerHTML += `<div>${p.title} × ${p.qty} – ${(p.qty * p.price).toFixed(2)} €</div>`;
  });
  preview.style.display = preview.style.display === "block" ? "none" : "block";
}
window.onload = updateCartCount;
</script>

<!-- 🍪 COOKIE MODAL -->
<div id="cookie-modal" style="position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.8);display:flex;align-items:center;justify-content:center;z-index:9999;">
  <div style="background:#1e1e1e;color:white;padding:30px;border-radius:10px;max-width:600px;width:90%;max-height:80vh;overflow:hidden;box-shadow:0 0 20px #000;display:flex;flex-direction:column;">
    
    <h2 style="text-align:center;color:#4CAF50;">🍪 Cookie-Einstellungen</h2>

    <div style="overflow-y:auto;padding:10px;margin:10px 0;border:1px solid #333;border-radius:6px;background:#2a2a2a;flex-grow:1;">
      <p>
        Wir verwenden Cookies zur Verbesserung deines Einkaufserlebnisses. Dazu gehören essentielle Funktionen wie der Warenkorb, Spracheinstellungen, Sicherheits-Token, Session-IDs und mehr.
      </p>
      <p>
        Du kannst deine Entscheidung jederzeit ändern. Weitere Infos findest du in unserer Datenschutzerklärung.
      </p>
      <p> 
      <h3>1. Allgemeines</h3>
Diese Cookie-Richtlinie informiert Sie über die Verwendung von Cookies auf 
unserer Website gemäß Art. 13 DSGVO. Wir, Fitgear, betreiben diesen Onlineshop 
(nachfolgend „Website“) und setzen Cookies ein, um Ihnen eine benutzerfreundliche 
und sichere Nutzung unseres Angebots zu ermöglichen.

<h3>2. Was sind Cookies?</h3>
Cookies sind kleine Textdateien, die durch Ihren Browser auf Ihrem Endgerät gespeichert werden, 
wenn Sie unsere Website besuchen. Diese Dateien enthalten Informationen, 
die eine Wiedererkennung des Browsers ermöglichen und bestimmte Funktionen unserer Website 
sicherstellen.

<h3>3. Arten von Cookies</h3>
Wir verwenden folgende Arten von Cookies:

a) Technisch notwendige Cookies
Rechtsgrundlage: Art. 6 Abs. 1 lit. b und f DSGVO
Diese Cookies sind für den Betrieb der Website erforderlich. Ohne diese Cookies funktioniert z. B. der Warenkorb oder der Login-Bereich nicht. Dazu zählen:

Session-ID (zur Wiedererkennung während Ihres Besuchs)

Authentifizierung und Login-Zustand

Speicherung Ihrer Cookie-Einstellungen

Warenkorb-Funktion

Sicherheitsfunktionen (z. B. CSRF-Schutz)

b) Funktionale Cookies
Rechtsgrundlage: Art. 6 Abs. 1 lit. a DSGVO (Einwilligung)
Diese Cookies ermöglichen zusätzliche Funktionen, z. B.:

Speicherung von Sprache, Region oder Währung

Anzeige zuletzt angesehener Produkte

Personalisierte Benutzeroberfläche (z. B. Ansicht als Liste/Gitter)

c) Analyse- und Statistik-Cookies
Rechtsgrundlage: Art. 6 Abs. 1 lit. a DSGVO (Einwilligung)
Diese Cookies helfen uns, das Verhalten unserer Nutzer anonymisiert zu analysieren und unsere Website zu verbessern:

Besuchte Seiten und Kategorien

Verweildauer und Scrollverhalten

A/B-Tests zur Optimierung der Benutzererfahrung

d) Marketing- und Tracking-Cookies
Rechtsgrundlage: Art. 6 Abs. 1 lit. a DSGVO (Einwilligung)
Diese Cookies ermöglichen es, Ihnen personalisierte Werbung anzuzeigen und den Erfolg unserer Werbemaßnahmen zu messen:

Retargeting (z. B. über Google Ads, Meta/Facebook Pixel)

Affiliate-Tracking (Partnerprogramm-Zuordnung)

Benutzer-ID zur Wiedererkennung auf Drittseiten

<h3>4. Einwilligung und Widerruf</h3>
Nicht-notwendige Cookies werden nur gesetzt, wenn Sie darin ausdrücklich eingewilligt haben (Art. 7 DSGVO). Ihre Einwilligung erfolgt über unser Cookie-Banner und kann jederzeit widerrufen oder angepasst werden.

Cookie-Einstellungen ändern: [Link zu Cookie-Einstellungen einfügen]

<h3>5. Speicherdauer</h3>
Die Speicherdauer der Cookies variiert je nach Zweck:

Session-Cookies: werden nach dem Schließen des Browsers gelöscht.

Persistente Cookies: bleiben je nach Zweck mehrere Tage bis Monate gespeichert.

Eine detaillierte Aufstellung finden Sie in unserer [Cookie-Tabelle / Übersicht].

<h3>6. Drittanbieter-Cookies</h3>
Teilweise setzen wir Cookies von Drittanbietern ein (z. B. Google Analytics, Meta Pixel). 
Diese Anbieter können Daten in Drittländer wie die USA übertragen.
 Wir achten dabei auf den Abschluss entsprechender Verträge 
 (z. B. Standardvertragsklauseln gemäß Art. 46 DSGVO).

<h3>7. Ihre Rechte</h3>
Als Nutzer haben Sie gemäß DSGVO folgende Rechte:

Auskunft (Art. 15 DSGVO)

Berichtigung (Art. 16 DSGVO)

Löschung (Art. 17 DSGVO)

Einschränkung der Verarbeitung (Art. 18 DSGVO)

Datenübertragbarkeit (Art. 20 DSGVO)

Widerspruch gegen Verarbeitung (Art. 21 DSGVO)
      </p>
    </div>

    <button onclick="acceptCookies()" style="margin:15px auto 5px auto;padding:12px 30px;font-weight:bold;font-size:16px;background:#4CAF50;color:white;border:none;border-radius:6px;cursor:pointer;">
      ✅ Akzeptieren
    </button>
    <a href="#" onclick="declineCookies(); return false;" style="text-align:center;color:#aaa;font-size:12px;margin-top:10px;text-decoration:underline;">
      Ablehnen
    </a>
  </div>
</div>

<script>
function setConsent(status) {
  const expireAt = Date.now() + 7 * 24 * 60 * 60 * 1000; // 7 Tage
  const data = { status: status, expires: expireAt };
  localStorage.setItem("cookieConsent", JSON.stringify(data));
}

function getConsent() {
  const stored = localStorage.getItem("cookieConsent");
  if (!stored) return null;
  try {
    const obj = JSON.parse(stored);
    if (Date.now() > obj.expires) {
      localStorage.removeItem("cookieConsent");
      return null;
    }
    return obj.status;
  } catch {
    return null;
  }
}

function acceptCookies() {
  setConsent("accepted");
  document.getElementById("cookie-modal").style.display = "none";
  //location.reload(); 
}

function declineCookies() {
  setConsent("declined");
  document.getElementById("cookie-modal").style.display = "none";
}

window.addEventListener("DOMContentLoaded", () => {
  const consent = getConsent();
  if (!consent) {
    document.getElementById("cookie-modal").style.display = "flex";
  }
});
</script>




</body>




</html>