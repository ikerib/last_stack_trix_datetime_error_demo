import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['dialog', 'dynamicContent', 'loadingContent'];

    observer = null;

    connect() {
        if (this.hasDynamicContentTarget) {
            // when the content changes, call this.open()
            this.observer = new MutationObserver(() => {
                const shouldOpen = this.dynamicContentTarget.innerHTML.trim().length > 0;

                if (shouldOpen && !this.dialogTarget.open) {
                    this.open();
                } else if (!shouldOpen && this.dialogTarget.open) {
                    this.close();
                }
            });
            this.observer.observe(this.dynamicContentTarget, {
                childList: true,
                characterData: true,
                subtree: true
            });
        }
    }

    disconnect() {
        if (this.observer) {
            this.observer.disconnect();
        }
        if (this.dialogTarget.open) {
            this.close();
        }
    }

    open() {
        this.dialogTarget.showModal();
        //document.body.classList.add('overflow-hidden', 'blur-sm');
    }

    close() {
        if (this.hasDialogTarget) {
            this.dialogTarget.close();
        }
        //document.body.classList.remove('overflow-hidden', 'blur-sm');
    }

    clickOutside(event) {
        if (event.target !== this.dialogTarget) {
            return;
        }

        if (!this.#isClickInElement(event, this.dialogTarget)) {
            this.dialogTarget.close();
        }
    }

    showLoading() {
        // do nothing if the dialog is already open
        if (this.dialogTarget.open) {
            return;
        }

        this.dynamicContentTarget.innerHTML = this.loadingContentTarget.innerHTML;
    }

    #isClickInElement(event, element) {
        const rect = element.getBoundingClientRect();
        return (
            rect.top <= event.clientY &&
            event.clientY <= rect.top + rect.height &&
            rect.left <= event.clientX &&
            event.clientX <= rect.left + rect.width
        );
    }
}
