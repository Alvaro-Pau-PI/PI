document.addEventListener('DOMContentLoaded', function () {

    const commentsList = document.getElementById('comments-list');
    const commentForm = document.getElementById('commentForm');
    const formContainer = document.getElementById('comment-form-container');
    const loginPrompt = document.getElementById('login-prompt');

    // 1. Gestionar visibilidad del formulario
    if (typeof IS_LOGGED_IN !== 'undefined' && IS_LOGGED_IN) {
        if (formContainer) formContainer.style.display = 'block';
        if (loginPrompt) loginPrompt.style.display = 'none';
    } else {
        if (formContainer) formContainer.style.display = 'none';
        if (loginPrompt) loginPrompt.style.display = 'block';
    }

    // 2. Cargar comentarios al inicio
    if (typeof PRODUCT_ID !== 'undefined') {
        loadComments();
    }

    // 3. Enviar comentario
    if (commentForm) {
        commentForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const text = document.getElementById('commentText').value.trim();
            const ratingInput = document.querySelector('input[name="rating"]:checked');
            const rating = ratingInput ? ratingInput.value : 0;
            const submitBtn = commentForm.querySelector('button[type="submit"]');

            if (!text) {
                alert("Por favor, escribe un comentario.");
                return;
            }

            if (!rating) {
                alert("Por favor, selecciona una puntuación.");
                return;
            }

            // Deshabilitar botón para evitar doble envío
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = "Publicando...";
            }

            const data = {
                product_id: PRODUCT_ID,
                text: text,
                rating: rating
            };

            fetch('api_comentaris.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
                .then(response => response.json())
                .then(result => {
                    if (result.error) {
                        alert("Error: " + result.error);
                    } else {
                        // Limpiar formulario
                        document.getElementById('commentText').value = '';
                        if (ratingInput) ratingInput.checked = false;

                        // Recargar comentarios (o añadir dinámicamente)
                        loadComments();
                    }
                })
                .catch(err => console.error('Error:', err))
                .finally(() => {
                    // Rehabilitar botón
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.textContent = "Publicar comentari";
                    }
                });
        });
    }

    // Función para cargar comentarios
    function loadComments() {
        fetch(`api_comentaris.php?product_id=${PRODUCT_ID}`)
            .then(response => response.json())
            .then(comments => {
                renderComments(comments);
            })
            .catch(err => {
                console.error('Error cargando comentarios:', err);
                if (commentsList) commentsList.innerHTML = '<p>Error al cargar comentarios.</p>';
            });
    }

    // Función para renderizar comentarios
    function renderComments(comments) {
        if (!commentsList) return;

        if (!comments || comments.length === 0) {
            commentsList.innerHTML = '<p style="text-align: center; color: #777;">Aún no hay comentarios. ¡Sé el primero en opinar!</p>';
            return;
        }

        commentsList.innerHTML = '';
        comments.forEach(comment => {
            const div = document.createElement('div');
            div.className = 'comment-item';

            const stars = '★'.repeat(comment.rating) + '☆'.repeat(5 - comment.rating);
            const date = new Date(comment.data).toLocaleDateString();

            let deleteBtn = '';
            if (comment.can_delete) {
                deleteBtn = `<button class="delete-btn" onclick="deleteComment('${comment.id}')">Eliminar</button>`;
            }

            div.innerHTML = `
                <div class="comment-header">
                    <span class="comment-user">${escapeHtml(comment.user_name)}</span>
                    <span>${date}</span>
                </div>
                <div class="comment-stars">${stars}</div>
                <div class="comment-text">
                    ${escapeHtml(comment.text)}
                    ${deleteBtn}
                </div>
            `;
            commentsList.appendChild(div);
        });
    }

    // Función global para borrar (necesaria para onclick)
    window.deleteComment = function (id) {
        if (!confirm("¿Estás seguro de que quieres eliminar este comentario?")) return;

        fetch(`api_comentaris.php?id=${id}`, {
            method: 'DELETE'
        })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    loadComments();
                } else {
                    alert("Error al eliminar: " + (result.error || 'Desconocido'));
                }
            })
            .catch(err => console.error('Error:', err));
    };

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
