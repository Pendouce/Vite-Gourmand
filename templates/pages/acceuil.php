<?php require_once(APP_ROOT.'/templates/layouts/header.php');?>

  <div class="p-8 space-y-8">

  <!-- Couleurs -->
  <section>
    <h2 class="font-h2 text-xl mb-4">Couleurs</h2>
    <div class="flex gap-4">
      <div class="bg-primary text-fond-carte p-4 rounded">primary</div>
      <div class="bg-fond-carte text-texte border border-bordure p-4 rounded">fond-carte</div>
      <div class="bg-fond-section text-texte p-4 rounded">fond-section</div>
      <div class="bg-border border-2 border-bordure text-texte p-4 rounded">bordure</div>
    </div>
  </section>

  <!-- Polices -->
  <section>
    <h1 class="font-h1 text-3xl text-texte">Titre H1 - Playfair Display</h1>
    <h2 class="font-h2 text-2xl text-texte">Titre H2 - Lora</h2>
    <p class="font-body text-texte">Texte courant en Literata, pour vérifier la lisibilité du corps de texte sur plusieurs lignes.</p>
    <p class="font-logo text-texte">VITE & GOURMAND</p>
  </section>

  <!-- Graisses -->
  <section>
    <p class="font-body font-normal">Literata normal (400)</p>
    <p class="font-body font-medium">Literata medium (500)</p>
    <p class="font-body font-semibold">Literata semibold (600)</p>
    <p class="font-body font-bold">Literata bold (700)</p>
  </section>

</div>

<?php require_once(APP_ROOT.'/templates/layouts/footer.php');?>