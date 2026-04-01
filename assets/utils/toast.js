/**
 * Toast Notification System
 */

export function showToast(message, type = 'info', duration = 5000) {
    const container = document.getElementById('toast-container')
    if (!container) {
        console.warn('Toast container not found in DOM')
        return
    }

    const toast = document.createElement('div')
    toast.className = getToastClasses(type)
    
    const icon = getToastIcon(type)
    const closeButton = '✕'
    
    toast.innerHTML = `
        <div class="flex items-center gap-3 flex-1">
            <span class="text-lg flex-shrink-0">${icon}</span>
            <span>${message}</span>
        </div>
        <button class="text-lg flex-shrink-0 opacity-70 hover:opacity-100 transition-opacity" onclick="this.parentElement.remove()">
            ${closeButton}
        </button>
    `

    container.appendChild(toast)

    // Auto remove after duration
    if (duration > 0) {
        setTimeout(() => {
            toast.remove()
        }, duration)
    }

    return toast
}

function getToastClasses(type) {
    const baseClasses = `
        flex items-center gap-3 justify-between
        px-6 py-4
        rounded-2xl
        shadow-lg
        animate-slide-in
        mb-3
        font-medium
        max-w-md
    `

    const typeClasses = {
        'success': 'bg-emerald-500 text-white',
        'error': 'bg-red-500 text-white',
        'warning': 'bg-amber-500 text-white',
        'info': 'bg-blue-500 text-white'
    }

    return `${baseClasses} ${typeClasses[type] || typeClasses['info']}`
}

function getToastIcon(type) {
    const icons = {
        'success': '✓',
        'error': '✕',
        'warning': '⚠',
        'info': 'ℹ'
    }
    return icons[type] || icons['info']
}

export const Toast = {
    success: (message) => showToast(message, 'success'),
    error: (message) => showToast(message, 'error'),
    warning: (message) => showToast(message, 'warning'),
    info: (message) => showToast(message, 'info')
}

/**
 * Dispatch custom event to notify listeners
 */
export function dispatchEvent(eventName, detail = {}) {
    document.dispatchEvent(new CustomEvent(eventName, { detail }))
}
