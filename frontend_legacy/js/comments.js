document.addEventListener('DOMContentLoaded', function () {

    const commentsList = document.getElementById('comments-list');
    const commentForm = document.getElementById('commentForm');
    const formContainer = document.getElementById('comment-form-container');
    const loginPrompt = document.getElementById('login-prompt');
    const msgBox = document.getElementById('msg-feedback');

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

    // Función auxiliar para mostrar mensajes verdes/rojos
    function showMessage(message, isError = false) {
        if (!msgBox) return;
        msgBox.textContent = message;
        msgBox.className = isError ? 'msg-error' : 'msg-success';
        msgBox.style.display = 'block';

        // Ocultar mensaje después de 3 segundos
        setTimeout(() => {
            msgBox.style.display = 'none';
        }, 3000);
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
                showMessage("Escribe algo, por favor.", true);
                return;
            }
            if (!rating) {
                showMessage("Selecciona las estrellas.", true);
                return;
            }

            // Bloquear botón
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = "Enviant...";
            }

            const data = {
                product_id: PRODUCT_ID,
                text: text,
                rating: rating
            };

            fetch('api_comentaris.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
                .then(response => response.text())
                .then(text => {
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        throw new Error("Invalid JSON response");
                    }
                })
                .then(result => {
                    if (result.error) {
                        showMessage("Error: " + result.error, true);
                    } else {
                        // ÉXITO
                        document.getElementById('commentText').value = ''; // Limpiar texto
                        if (ratingInput) ratingInput.checked = false;      // Limpiar estrellas

                        showMessage("¡Comentari publicat correctament!");

                        // ACTUALIZAR AL INSTANTE
                        loadComments();
                    }
                })
                .catch(err => {
                    console.error('Error:', err);
                    showMessage("Error de connexió.", true);
                })
                .finally(() => {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.textContent = "Publicar comentari";
                    }
                });
        });
    }

    // Función para cargar comentarios
    function loadComments() {
        // Usamos timestamp (?t=...) para forzar al navegador a no usar caché
        fetch(`api_comentaris.php?product_id=${PRODUCT_ID}&t=${new Date().getTime()}`)
            .then(response => response.json())
            .then(comments => {
                renderComments(comments);
            })
            .catch(err => {
                console.error('Error loading comments:', err);
                if (commentsList) commentsList.innerHTML = '<p>Error carragant.</p>';
            });
    }

    function renderComments(comments) {
        if (!commentsList) return;

        if (!comments || comments.length === 0) {
            commentsList.innerHTML = '<p style="text-align: center; color: #777;">Encara no hi ha comentaris. Sigues el primer!</p>';
            return;
        }

        // Ordenar: el más nuevo arriba
        comments.sort((a, b) => new Date(b.data) - new Date(a.data));

        commentsList.innerHTML = '';
        comments.forEach(comment => {
            const div = document.createElement('div');
            div.className = 'comment-item';

            const stars = '★'.repeat(comment.rating) + '☆'.repeat(5 - comment.rating);
            const dateObj = new Date(comment.data);
            const dateStr = dateObj.toLocaleDateString();

            let deleteBtn = '';
            if (comment.can_delete) {
                deleteBtn = `<button class="delete-btn" onclick="deleteComment('${comment.id}')">Esborrar</button>`;
            }

            div.innerHTML = `
                <div class="comment-header">
                    <div>
                        <span class="comment-user">${escapeHtml(comment.user_name)}</span>
                        <span style="font-size:0.85em; color:#666; margin-left:10px;">${dateStr}</span>
                    </div>
                    <div class="comment-stars">${stars}</div>
                </div>
                <div class="comment-text">${escapeHtml(comment.text)}</div>
                <div style="text-align: right; margin-top: 5px;">${deleteBtn}</div>
            `;
            commentsList.appendChild(div);
        });
    }

    // Función global para borrar (necesaria para onclick)
    window.deleteComment = function (id) {
        // if (!confirm("Segur que vols esborrar-ho?")) return;

        fetch(`api_comentaris.php?id=${id}`, { method: 'DELETE' })
            .then(response => response.text())
            .then(text => {
                try {
                    return JSON.parse(text);
                } catch (e) {
                    throw new Error("Invalid JSON response");
                }
            })
            .then(result => {
                if (result.success) {
                    loadComments();
                } else {
                    showMessage("Error: " + (result.error || 'Desconocido'), true);
                }
            })
            .catch(err => {
                console.error('Error:', err);
                showMessage("Error al esborrar.", true);
            });
    };

    function escapeHtml(text) {
        if (!text) return '';
        return text.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
    }
});