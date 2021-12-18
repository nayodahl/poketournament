import { Controller } from 'stimulus';

export default class extends Controller {
    connect() {
        this.count = 0;

        var hello = () => {
            this.count++;
            this.element.innerHTML = '<i class="fa fa-thumbs-up"></i> ' + this.count;
        }

        this.element.addEventListener("click", hello);
    }
}
