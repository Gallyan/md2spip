import './bootstrap';

// Note: Alpine est géré automatiquement par Livewire 3
// Ne pas importer ni démarrer Alpine manuellement

// Intercepteur global pour gérer l'expiration de session (erreur 419)
// API Livewire 3.x : Livewire.hook('request', ...)
document.addEventListener('livewire:init', () => {
    Livewire.hook('request', ({ fail }) => {
        fail(({ status, preventDefault }) => {
            if (status === 419) {
                preventDefault();
                window.dispatchEvent(new CustomEvent('session-expired'));
            }
        });
    });
});
