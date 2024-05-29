import ModalForm from 'core_form/modalform';

export const init = (formClass) => {
    const modalForm = new ModalForm({
        // Name of the class where form is defined (must extend \core_form\dynamic_form):
        formClass: formClass,
        // Pass any configuration settings to the modal dialogue, for example, the title:
        modalConfig: {title: 'TEMP TITLE'},
        // DOM element that should get the focus after the modal dialogue is closed:
        returnFocus: document.body,
    });
    modalForm.addEventListener(modalForm.events.FORM_SUBMITTED, (e) => window.console.log(e.detail));
    modalForm.show();
};
