import { usePage } from "@inertiajs/vue3";
import { Notify } from "quasar";

export default function processFlashMessage() {
  const page = usePage();
  let flash = page.props.flash;
  let options;
  if (flash.info) {
    options = {
      message: flash.info,
      icon: 'info',
      color: 'blue',
    };
  }
  else if (flash.success) {
    options = {
      message: flash.success,
      icon: 'check',
      color: 'green',
    };
  }
  else if (flash.warning) {
    options = {
      message: flash.warning,
      icon: 'warning',
      color: 'orange',
    };
  }
  else if (flash.error) {
    options = {
      message: flash.error,
      icon: 'error',
      color: 'red',
    };
  }
  else {
    return;
  }

  Notify.create(options);
}
