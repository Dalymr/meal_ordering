<?php
// public/about.php
include '../includes/header.php';
?>

<div class="row">
  <div class="col-lg-8">
    <div class="card shadow-sm mb-4">
      <div class="card-header bg-primary text-white">
        <h4 class="mb-0"><i class="fas fa-info-circle me-2"></i>À propos de FoodFrenzy</h4>
      </div>
      <div class="card-body">
        <h5 class="border-bottom pb-2">Notre Concept</h5>
        <p>
          Bienvenue sur FoodFrenzy, votre plateforme de commande de repas en ligne lancée en 2024.
          Notre mission est de vous offrir une expérience culinaire exceptionnelle depuis le confort de votre domicile.
        </p>
        
        <h5 class="border-bottom pb-2 mt-4">Notre Approche</h5>
        <div class="row mt-3">
          <div class="col-md-6 mb-3">
            <div class="d-flex">
              <div class="flex-shrink-0">
                <i class="fas fa-carrot fa-2x text-primary"></i>
              </div>
              <div class="flex-grow-1 ms-3">
                <h6>Fraicheur</h6>
                <p class="text-muted">Nous sélectionnons uniquement des ingrédients frais et de saison pour garantir la qualité de vos plats.</p>
              </div>
            </div>
          </div>
          
          <div class="col-md-6 mb-3">
            <div class="d-flex">
              <div class="flex-shrink-0">
                <i class="fas fa-shipping-fast fa-2x text-primary"></i>
              </div>
              <div class="flex-grow-1 ms-3">
                <h6>Rapidité</h6>
                <p class="text-muted">Notre système de livraison optimisé assure une livraison rapide pour que vos plats arrivent chauds.</p>
              </div>
            </div>
          </div>
          
          <div class="col-md-6 mb-3">
            <div class="d-flex">
              <div class="flex-shrink-0">
                <i class="fas fa-utensils fa-2x text-primary"></i>
              </div>
              <div class="flex-grow-1 ms-3">
                <h6>Diversité</h6>
                <p class="text-muted">Notre menu varié propose des plats de diverses cuisines du monde pour satisfaire toutes vos envies.</p>
              </div>
            </div>
          </div>
          
          <div class="col-md-6 mb-3">
            <div class="d-flex">
              <div class="flex-shrink-0">
                <i class="fas fa-leaf fa-2x text-primary"></i>
              </div>
              <div class="flex-grow-1 ms-3">
                <h6>Respect</h6>
                <p class="text-muted">Nous nous engageons à minimiser notre impact environnemental avec des emballages éco-responsables.</p>
              </div>
            </div>
          </div>
        </div>
        
        <h5 class="border-bottom pb-2 mt-4">Notre Équipe</h5>
        <p class="mt-3">
          Derrière FoodFrenzy se trouve une équipe passionnée de cuisiniers, développeurs et logisticiens 
          travaillant ensemble pour offrir le meilleur service possible. Fondée en 2024/2025, 
          notre startup est en constante évolution pour améliorer votre expérience culinaire.
        </p>
      </div>
    </div>
  </div>
  
  <div class="col-lg-4">
    <div class="card shadow-sm mb-4">
      <div class="card-body text-center">
        <i class="fas fa-utensils fa-4x text-primary mb-3"></i>
        <h5>FoodFrenzy en chiffres</h5>
        <div class="row mt-4">
          <div class="col-6 mb-3">
            <div class="h3 mb-0 text-primary">50+</div>
            <div class="small text-muted">Plats disponibles</div>
          </div>
          <div class="col-6 mb-3">
            <div class="h3 mb-0 text-primary">15</div>
            <div class="small text-muted">Cuisiniers experts</div>
          </div>
          <div class="col-6 mb-3">
            <div class="h3 mb-0 text-primary">30min</div>
            <div class="small text-muted">Temps de livraison</div>
          </div>
          <div class="col-6 mb-3">
            <div class="h3 mb-0 text-primary">1000+</div>
            <div class="small text-muted">Clients satisfaits</div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="card shadow-sm">
      <div class="card-header bg-dark text-white">
        <h5 class="mb-0">Soutien à la Palestine</h5>
      </div>
      <div class="card-body">
        <div class="text-center mb-3">
          <img src="/img/palestine-flag.png" alt="Palestine Flag" class="img-fluid" style="max-width: 100px;"
               onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/0/00/Flag_of_Palestine.svg'; this.onerror=null;">
        </div>
        <p>
          Nous soutenons le peuple palestinien dans sa lutte pour la liberté et la dignité. 
          Viva Palestina!
        </p>
        <div class="d-grid">
          <a href="https://www.palestinercs.org/en" target="_blank" class="btn btn-success">Faire un don</a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include '../includes/footer.php'; ?>
