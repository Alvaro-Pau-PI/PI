/**
 * Reviews JS Functionality
 * Handles loading and submitting reviews via AJAX/Fetch.
 */

document.addEventListener('DOMContentLoaded', () => {

    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    const modal = document.getElementById('reviews-modal');
    const reviewsList = document.getElementById('reviews-list');
    const reviewForm = document.getElementById('review-form');
    const closeBtn = document.querySelector('.close-modal');

    // Auth state check (simplified, relies on presence of form)
    const isAuthenticated = !!reviewForm;

    // Helper: Fetch wrapper with headers
    async function apiFetch(url, options = {}) {
        const headers = {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': CSRF_TOKEN
        };

        // Ensure credentials are sent for Sanctum
        options.credentials = 'include';
        options.headers = { ...headers, ...options.headers };

        const response = await fetch(url, options);
        return response;
    }

    // Open Modal and Load Reviews
    window.openReviewsModal = async (productId, productName) => {
        if (!modal) return;

        document.getElementById('modal-product-name').textContent = productName;
        modal.dataset.productId = productId;
        modal.style.display = 'flex'; // Show modal

        // Clear previous state
        reviewsList.innerHTML = '<p>Carregant valoracions...</p>';
        if (reviewForm) {
            reviewForm.reset();
            document.getElementById('review-feedback').innerHTML = '';
        }

        // Fetch Reviews
        try {
            const res = await apiFetch(`/api/products/${productId}/reviews`);
            if (!res.ok) throw new Error('Error carregant valoracions');

            const reviews = await res.json();
            renderReviews(reviews);
        } catch (error) {
            console.error(error);
            reviewsList.innerHTML = '<p class="error">No s\'han pogut carregar les valoracions.</p>';
        }
    };

    // Render Reviews List
    function renderReviews(reviews) {
        reviewsList.innerHTML = '';
        if (reviews.length === 0) {
            reviewsList.innerHTML = '<p>Encara no hi ha valoracions. Sigues el primer!</p>';
            return;
        }

        reviews.forEach(review => {
            const date = new Date(review.created_at).toLocaleDateString();
            const stars = '★'.repeat(review.rating || 0) + '☆'.repeat(5 - (review.rating || 0));

            const item = document.createElement('div');
            item.classList.add('review-item');
            item.innerHTML = `
                <div class="review-header">
                    <strong>${review.user ? review.user.name : 'Usuari'}</strong>
                    <span class="review-date">${date}</span>
                </div>
                <div class="review-stars">${stars}</div>
                <p class="review-text">${escapeHtml(review.text)}</p>
            `;
            reviewsList.appendChild(item);
        });
    }

    // Handle Review Submission
    if (reviewForm) {
        reviewForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const productId = modal.dataset.productId;
            const text = document.getElementById('review-text').value;
            const rating = document.getElementById('review-rating').value;
            const feedback = document.getElementById('review-feedback');

            // Client-side Validation
            if (!text.trim()) {
                feedback.innerHTML = '<span class="error">El comentari no pot estar buit.</span>';
                return;
            }
            if (!rating) {
                feedback.innerHTML = '<span class="error">Selecciona una puntuació.</span>';
                return;
            }

            feedback.innerHTML = '<span class="info">Enviant...</span>';

            try {
                const res = await apiFetch(`/api/products/${productId}/reviews`, {
                    method: 'POST',
                    body: JSON.stringify({ text, rating })
                });

                if (res.ok) {
                    const newReview = await res.json();
                    feedback.innerHTML = '<span class="success">Valoració enviada correctament!</span>';
                    reviewForm.reset();
                    // Append new review or reload
                    // Simple reload for now to correct order/structure
                    loadReviewsForCurrent();
                } else {
                    const errData = await res.json();
                    feedback.innerHTML = `<span class="error">${errData.message || 'Error validació'}</span>`;
                }
            } catch (error) {
                feedback.innerHTML = '<span class="error">Error de xarxa. Torna-ho a provar.</span>';
            }
        });
    }

    async function loadReviewsForCurrent() {
        const productId = modal.dataset.productId;
        const res = await apiFetch(`/api/products/${productId}/reviews`);
        if (res.ok) {
            const reviews = await res.json();
            renderReviews(reviews);
        }
    }

    // Modal Closing Logic
    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            modal.style.display = 'none';
        });
    }

    window.addEventListener('click', (e) => {
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });

    // Helper
    function escapeHtml(text) {
        if (!text) return '';
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

});
