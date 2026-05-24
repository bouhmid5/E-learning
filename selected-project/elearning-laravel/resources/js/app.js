import '../css/app.css';

document.addEventListener('submit', (event) => {
    const form = event.target;

    if (!(form instanceof HTMLFormElement)) {
        return;
    }

    const confirmation = form.dataset.confirm;

    if (confirmation && ! window.confirm(confirmation)) {
        event.preventDefault();
        return;
    }

    const submitter = form.querySelector('button[type="submit"]');

    if (submitter) {
        submitter.dataset.originalText = submitter.textContent;
        submitter.textContent = submitter.dataset.loadingText || 'Traitement...';
        submitter.disabled = true;
    }
});
