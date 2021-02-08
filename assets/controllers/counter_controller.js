import { Controller } from 'stimulus';

export default class extends Controller {
    connect() {
        this.count = 0;

        var hello = () => {
            this.count++;
            this.element.innerHTML = this.count;
        }

        this.element.addEventListener("click", hello);
    }
}
