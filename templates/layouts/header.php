<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="asset/css/style.css">
  <title>Document</title>
</head>
<body>
  <header class="bg-fond-nav p-4 flex items-center mb-6 lg:justify-between relative sticky top-0 z-50">
    <!-- Burger mobile -->
     <div class="flex-1 flex lg:hidden items-center">
      <button id="btnBurger" type="button" aria-label="Menu" aria-expanded="false">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-8 text-texte">
          <path fill-rule="evenodd" d="M3 6.75A.75.75 0 0 1 3.75 6h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 6.75ZM3 12a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 12Zm0 5.25a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
        </svg>
      </button>
     </div>
    <!-- Logo header -->
    <div class="flex-1 order-2 lg:flex-none lg:order-0">
    <!-- <div> -->
      <div class="flex flex-col items-center font-logo lg:text-lg text-primary text-center w-fit leading-none">
        <a href="/">
          <p class="m-0">Vite</p>
          <p class="m-0">&</p>
          <p class="m-0">Gourmand</p>
        </a>
      </div>
    </div>

    <?php /** @var array $navRole */ ?>
    <?php /** @var array $navConnexion */ ?>
    <?php /** @var array $estConnecte */ ?>
    <?php /** @var string $csrfToken */ ?>

    <!-- Liens nav descktop -->
    <nav class="flex-1 flex order-3 items-center justify-end gap-6 lg:flex-none lg:order-0 lg:justify-start">
      <div id="navLiens" class="hidden lg:block absolute lg:static top-full left-0 w-full">
        <ul class="flex flex-col gap-4 px-12 py-6 bg-fond-nav divide-y divide-texte/20 lg:flex-row lg:items-center lg:gap-8 lg:py-0 lg:divide-none">
          <?php foreach($navRole as $label):?>
            <li class="py-2"><a href="<?= $label['url'] ?>"><?= $label['label'] ?></a></li>
          <?php endforeach; ?>
        </ul>
      </div>
      
      <!-- Bouton connexion -->
      <!-- <div>
        <a class="btn" href="<?= $navConnexion['url'] ?>"> <?= $navConnexion['label'] ?></a>
      </div> -->
      <div>
        <?php if($estConnecte): ?>
          <form action="<?= $navConnexion['url'] ?>" method="post">
            <input type="hidden" name="csrfToken" value="<?= $csrfToken ?>">
            <button type="submit" class="btn"><?= $navConnexion['label'] ?></button>
          </form>
        <?php else: ?>
          <a class="btn" href="<?= $navConnexion['url'] ?>"><?= $navConnexion['label'] ?></a>
        <?php endif; ?>
      </div>

    </nav>
  </header>
  <main>