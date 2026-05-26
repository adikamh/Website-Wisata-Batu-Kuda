document.addEventListener('DOMContentLoaded', () => {
    const adminButton = document.getElementById('adminCanvasButton');
    const modal = document.getElementById('adminCanvasModal');
    const closeButton = document.getElementById('canvasModalClose');
    const cancelButton = document.getElementById('canvasCancelButton');
    const saveButton = document.getElementById('canvasSaveButton');
    const form = document.getElementById('canvasEditorForm');

    if (!adminButton || !modal || !form || !saveButton) {
        return;
    }

    const parseJson = (value) => {
        try {
            return JSON.parse(value || '{}');
        } catch (error) {
            return {};
        }
    };

    const config = {
        updateUrl: modal.dataset.updateUrl || '',
        csrfToken: modal.dataset.csrfToken || document.querySelector('meta[name="csrf-token"]')?.content || '',
        content: parseJson(modal.dataset.content),
    };
    const contentPayload = config.content || {};
    const featuresContainer = document.getElementById('canvasFeaturesContainer');
    const tipsContainer = document.getElementById('canvasTipsContainer');
    const saveButtonDefaultText = saveButton.textContent;

    const setFieldValue = (id, value) => {
        const field = document.getElementById(id);

        if (field) {
            field.value = value || '';
        }
    };

    const toItemList = (items) => {
        if (Array.isArray(items)) {
            return items;
        }

        if (items && typeof items === 'object') {
            return Object.values(items);
        }

        return [];
    };

    const createTextElement = (tagName, text) => {
        const element = document.createElement(tagName);
        element.textContent = text;

        return element;
    };

    const createLabel = (text) => createTextElement('label', text);

    const createInput = (name, value) => {
        const input = document.createElement('input');
        input.type = 'text';
        input.name = name;
        input.value = value || '';
        input.maxLength = 120;

        return input;
    };

    const createTextarea = (name, value) => {
        const textarea = document.createElement('textarea');
        textarea.name = name;
        textarea.rows = 2;
        textarea.value = value || '';

        return textarea;
    };

    const createEditorItem = (type, item, index) => {
        const wrapper = document.createElement('div');
        wrapper.className = 'canvas-editor-item';

        wrapper.appendChild(createTextElement('strong', `${type.label} ${index + 1}`));
        wrapper.appendChild(createLabel('Judul'));
        wrapper.appendChild(createInput(`${type.name}[${index}][title]`, item?.title));
        wrapper.appendChild(createLabel('Deskripsi'));
        wrapper.appendChild(createTextarea(`${type.name}[${index}][description]`, item?.description));

        return wrapper;
    };

    const renderEditableList = (container, items, type) => {
        if (!container) {
            return;
        }

        container.replaceChildren();

        toItemList(items).forEach((item, index) => {
            container.appendChild(createEditorItem(type, item, index));
        });
    };

    const fillInputs = () => {
        setFieldValue('canvasAboutTitle', contentPayload.about_title);
        setFieldValue('canvasAboutSubtitle', contentPayload.about_subtitle);
        setFieldValue('canvasAboutDescription', contentPayload.about_description);
        setFieldValue('canvasInfoLocation', contentPayload.info_location);
        setFieldValue('canvasInfoOpeningHours', contentPayload.info_opening_hours);
        setFieldValue('canvasInfoTicketPrice', contentPayload.info_ticket_price);
        setFieldValue('canvasInfoContact', contentPayload.info_contact);

        renderEditableList(featuresContainer, contentPayload.features, {
            label: 'Fitur',
            name: 'features',
        });
        renderEditableList(tipsContainer, contentPayload.tips, {
            label: 'Panduan',
            name: 'tips',
        });
    };

    const toggleModal = (show) => {
        modal.classList.toggle('active', show);
        modal.setAttribute('aria-hidden', show ? 'false' : 'true');
    };

    const closeModal = () => toggleModal(false);

    const buildRequestBody = () => {
        const formData = new FormData(form);
        const body = {};

        for (const [key, value] of formData.entries()) {
            const groupedField = key.match(/^([^[\]]+)\[(\d+)]\[(.+)]$/);

            if (groupedField) {
                const [, parent, index, field] = groupedField;
                body[parent] = body[parent] || [];
                body[parent][Number(index)] = body[parent][Number(index)] || {};
                body[parent][Number(index)][field] = value;
            } else {
                body[key] = value;
            }
        }

        return body;
    };

    adminButton.addEventListener('click', () => {
        fillInputs();
        toggleModal(true);
    });

    closeButton?.addEventListener('click', closeModal);
    cancelButton?.addEventListener('click', closeModal);
    modal.addEventListener('click', (event) => {
        if (event.target === modal) {
            closeModal();
        }
    });

    saveButton.addEventListener('click', async () => {
        saveButton.disabled = true;
        saveButton.textContent = 'Menyimpan...';

        try {
            const response = await fetch(config.updateUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': config.csrfToken,
                    Accept: 'application/json',
                },
                body: JSON.stringify(buildRequestBody()),
            });
            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Gagal menyimpan konten.');
            }

            window.location.reload();
        } catch (error) {
            window.alert(error.message || 'Terjadi kesalahan saat menyimpan konten.');
        } finally {
            saveButton.disabled = false;
            saveButton.textContent = saveButtonDefaultText;
        }
    });
});
