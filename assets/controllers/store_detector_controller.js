import { Controller } from "@hotwired/stimulus"

export default class extends Controller {

    static targets = ["url", "label"]

    detect(event) {

        console.log("Detecting store from URL:", this.urlTarget.value)

        const urlInput = event.target

        const divUrl = urlInput.parentElement.parentElement
        const labelInput = divUrl.querySelector('[data-store-detector-target="label"]')
        
        if (!labelInput) {
            console.warn("label input not found")
            return
        }

        const url = urlInput.value

        if (/amazon\./i.test(url)) {
            labelInput.value = "Amazon"
        }

        else if (/aubert\.com/i.test(url)) {
            labelInput.value = "Aubert"
        }

        else if (/vertbaudet/i.test(url)) {
            labelInput.value = "Vertbaudet"
        }

        else if (/ikea\.com/i.test(url)) {
            labelInput.value = "IKEA"
        }

        else if (/cdiscount\.com/i.test(url)) {
            labelInput.value = "Cdiscount"
        }

        else if (/bebe9\.com/i.test(url)) {
            labelInput.value = "Bébé9"
        }

    }
}