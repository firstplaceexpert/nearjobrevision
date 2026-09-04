import './bootstrap';

// NearJob Global Toast Notification Handler
window.addEventListener('notify', event => {
    const message = event.detail.message || event.detail;
    const toast = document.createElement('div');
    toast.className = 'fixed bottom-5 right-5 z-50 flex items-center gap-3 bg-slate-900 text-white px-5 py-3.5 rounded-xl shadow-2xl transition-all duration-300 transform translate-y-10 opacity-0';
    toast.innerHTML = `
        <svg class="w-5 h-5 text-indigo-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="text-sm font-medium">${message}</span>
    `;
    document.body.appendChild(toast);

    requestAnimationFrame(() => {
        toast.classList.remove('translate-y-10', 'opacity-0');
    });

    setTimeout(() => {
        toast.classList.add('translate-y-10', 'opacity-0');
        setTimeout(() => toast.remove(), 300);
    }, 3500);
});

// Touch & Mouse Swipe Card Manager
window.initSwipeCard = function (cardEl, jobId, livewireComponent) {
    if (!cardEl) return;

    let isDragging = false;
    let startX = 0;
    let startY = 0;
    let currentX = 0;
    let currentY = 0;

    const likeBadge = cardEl.querySelector('.swipe-badge-like');
    const nopeBadge = cardEl.querySelector('.swipe-badge-nope');

    function onPointerDown(e) {
        if (e.target.closest('button') || e.target.closest('a')) return;
        isDragging = true;
        cardEl.classList.add('dragging');
        startX = e.clientX || (e.touches && e.touches[0].clientX) || 0;
        startY = e.clientY || (e.touches && e.touches[0].clientY) || 0;
    }

    function onPointerMove(e) {
        if (!isDragging) return;
        const x = e.clientX || (e.touches && e.touches[0].clientX) || 0;
        const y = e.clientY || (e.touches && e.touches[0].clientY) || 0;
        
        currentX = x - startX;
        currentY = y - startY;

        const rotate = currentX * 0.08;
        cardEl.style.transform = `translate3d(${currentX}px, ${currentY}px, 0) rotate(${rotate}deg)`;

        // Update swipe badge opacity
        const opacity = Math.min(Math.abs(currentX) / 100, 1);
        if (currentX > 0) {
            if (likeBadge) likeBadge.style.opacity = opacity;
            if (nopeBadge) nopeBadge.style.opacity = 0;
        } else {
            if (nopeBadge) nopeBadge.style.opacity = opacity;
            if (likeBadge) likeBadge.style.opacity = 0;
        }
    }

    function onPointerUp() {
        if (!isDragging) return;
        isDragging = false;
        cardEl.classList.remove('dragging');

        const threshold = 120;

        if (currentX > threshold) {
            // Swipe Right (Lamar)
            executeSwipe('right');
        } else if (currentX < -threshold) {
            // Swipe Left (Pass)
            executeSwipe('left');
        } else {
            // Reset position
            cardEl.style.transform = 'translate3d(0, 0, 0) rotate(0deg)';
            if (likeBadge) likeBadge.style.opacity = 0;
            if (nopeBadge) nopeBadge.style.opacity = 0;
        }

        currentX = 0;
        currentY = 0;
    }

    function executeSwipe(direction) {
        const flyX = direction === 'right' ? window.innerWidth : -window.innerWidth;
        const rotate = direction === 'right' ? 30 : -30;
        cardEl.style.transition = 'transform 0.4s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.4s ease';
        cardEl.style.transform = `translate3d(${flyX}px, ${currentY}px, 0) rotate(${rotate}deg)`;
        cardEl.style.opacity = '0';

        if (direction === 'right' && likeBadge) likeBadge.style.opacity = 1;
        if (direction === 'left' && nopeBadge) nopeBadge.style.opacity = 1;

        setTimeout(() => {
            livewireComponent.swipe(jobId, direction);
        }, 300);
    }

    // Attach listeners
    cardEl.addEventListener('mousedown', onPointerDown);
    window.addEventListener('mousemove', onPointerMove);
    window.addEventListener('mouseup', onPointerUp);

    cardEl.addEventListener('touchstart', onPointerDown, { passive: true });
    window.addEventListener('touchmove', onPointerMove, { passive: true });
    window.addEventListener('touchend', onPointerUp);

    // Provide programmatic triggers for button clicks (Pass / Apply buttons)
    cardEl.triggerSwipe = function(direction) {
        currentY = 0;
        executeSwipe(direction);
    };
};
