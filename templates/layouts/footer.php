  </body>
  <footer class="bg-border p-8 text-texte text-xl">

    <?php /** @var object $infos */ ?>
    <div class="w-full flex items-center gap-8">
      

      <div class="flex justify-start items-center">
        <img class="h-30 w-50 object-cover" src="/asset/images/logo.png" alt="logo vite et gourmand">
      </div>
      <div class="w-full">

        <div class="grid grid-cols-1 md:grid-cols-3 mb-8 gap-8 w-full">
          <div class="flex flex-col items-center">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="m-4 size-6">
              <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 6a.75.75 0 0 0-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 0 0 0-1.5h-3.75V6Z" clip-rule="evenodd" />
            </svg>
            
            <p><?= htmlspecialchars($infos->getHorairesSemaine()) ?></p>
            <p><?= htmlspecialchars($infos->getHorairesWeekend()) ?></p>
          </div>
          <div class="flex flex-col items-center">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="m-4 size-6">
              <path fill-rule="evenodd" d="m11.54 22.351.07.04.028.016a.76.76 0 0 0 .723 0l.028-.015.071-.041a16.975 16.975 0 0 0 1.144-.742 19.58 19.58 0 0 0 2.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 0 0-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 0 0 2.682 2.282 16.975 16.975 0 0 0 1.145.742ZM12 13.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" clip-rule="evenodd" />
            </svg>
            
            <p><?= htmlspecialchars($infos->getAdresse()) ?></p>
          </div>
          <div class="flex flex-col items-center">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="m-4 size-5">
              <path fill-rule="evenodd" d="M1.5 4.5a3 3 0 0 1 3-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 0 1-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 0 0 6.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 0 1 1.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 0 1-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5Z" clip-rule="evenodd" />
            </svg>
            <p><?= htmlspecialchars($infos->getTelephone()) ?></p>
            <p><?= htmlspecialchars($infos->getEmail()) ?></p>
          </div>
        </div>
        
        <div class="border-t border-texte/30 max-w-7xl mx-auto">
          <div class="flex justify-center gap-4 mt-8">
            <p><a href="">Contact </a>| <a href="">Mention légales</a> | <a href="">CVG </a>| <a href="">Politique de confidentialité</a></p>
          </div>
          <div class="flex justify-center gap-4 mt-8">
            <p>© 2026 Vite & Gourmand - Tous droits réservés</p>
          </div>
        </div>
        
      </div>
    </div>
    
    
    
    
  </footer>
</html>