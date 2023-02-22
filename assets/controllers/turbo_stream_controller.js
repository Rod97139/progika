// import { Controller } from '@hotwired/stimulus';
// import { connectStreamSource, disconnectStreamSource } from "@hotwired/turbo";

// export default class extends Controller {
//   static values = { url: String };

  

//   connect() {
//     console.log(values);
//     this.es = new EventSource(this.urlValue);
//     console.log(this.urlValue);
//     console.log(this.es);
//     connectStreamSource(this.es);
//   }

//   disconnect() {
//     this.es.close();
//     disconnectStreamSource(this.es);
//   }
// }