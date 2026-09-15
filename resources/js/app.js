import './console-banner';

// Alpine est embarqué par Livewire : ne pas l'importer ni le démarrer ici

// Intercepteur global pour gérer l'expiration de session (erreur 419)
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
