// import { Controller } from '@hotwired/stimulus';

// export default class extends Controller {
//   static targets = ["input"];

//   connect() {

    // this.inputTargets.forEach((input) => {
    //   input.addEventListener("change", () => {
    //     const form = new FormData(this.element);
    //     const params = new URLSearchParams();
    //     form.forEach((value, key) => {
    //       if (value != "") {
    //         params.append(key, value);
    //       }
    //     });
    //     const url = new URL(window.location.href);
    //     fetch(`${url.pathname}?${params.toString()}&ajax=1`, {
    //       headers: {
    //         "X-Requested-With": "XMLHttpRequest",
    //       },
    //     })
    //       .then((response) => response.json())
    //       .then((data) => {
    //         const content = this.element.querySelector("#content");
    //         content.innerHTML = data.content;
    //         history.pushState({}, null, `${url.pathname}?${params.toString()}`);
    //       })
    //       .catch((e) => alert(e));
    //   });
    // });
//   }
// }