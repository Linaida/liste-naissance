import { Controller } from '@hotwired/stimulus'

export default class extends Controller {
    static targets = ['title', 'message', 'form', 'csrf']

    open(event) {
        const button = event.currentTarget

        const dialogId = button.dataset.dialogId
        const dialog = document.getElementById(dialogId)

        if (!dialog) return

        dialog.classList.remove('hidden')
        dialog.classList.add('flex')

        dialog.querySelector('[data-dialog-target="title"]').textContent =
            button.dataset.dialogTitle

        dialog.querySelector('[data-dialog-target="message"]').textContent =
            button.dataset.dialogMessage

        dialog.querySelector('[data-dialog-target="name"]').textContent =
            button.dataset.dialogName

        dialog.querySelector('form').action =
            button.dataset.dialogUrl

        dialog.querySelector('[data-dialog-target="csrf"]').value = button.dataset.dialogCsrf
    }

    close(event) {
        const dialog = event.currentTarget.closest('[data-controller="dialog"]')
        dialog.classList.add('hidden')
        dialog.classList.remove('flex')
    }
}