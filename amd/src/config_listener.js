import ModalFactory from 'core/modal_factory';

export const init = async() => {
    const modal = await ModalFactory.create({
        title: 'test title',
        body: '<p>Example body content</p>',
        footer: 'An example footer content',
    });

    modal.show();

    // ...
};