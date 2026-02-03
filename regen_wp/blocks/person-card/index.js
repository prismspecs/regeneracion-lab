(function (blocks, element, components, data, blockEditor, i18n) {
    const { registerBlockType } = blocks;
    const { createElement: el, useMemo } = element;
    const { PanelBody, SelectControl, ComboboxControl, Spinner } = components;
    const { useSelect } = data;
    const { InspectorControls, useBlockProps } = blockEditor;
    const { __ } = i18n;

    const variantOptions = [
        { label: __('About card', 'regen-wp'), value: 'about' },
        { label: __('Resident card', 'regen-wp'), value: 'resident' },
    ];

    registerBlockType('regen/person-card', {
        apiVersion: 2,
        title: __('Person Card', 'regen-wp'),
        description: __('Render a Person CPT card (About or Resident style).', 'regen-wp'),
        category: 'regen',
        icon: 'id-alt',
        attributes: {
            personId: { type: 'number' },
            variant: { type: 'string', default: 'about' },
        },
        edit: (props) => {
            const { attributes, setAttributes } = props;
            const { personId, variant } = attributes;

            const people = useSelect((select) => {
                return select('core').getEntityRecords('postType', 'person', {
                    per_page: -1,
                    _fields: ['id', 'title'],
                });
            }, []);

            const isResolving = useSelect((select) => {
                return select('core').isResolving('core', 'getEntityRecords', [
                    'postType',
                    'person',
                    { per_page: -1, _fields: ['id', 'title'] },
                ]);
            }, []);

            const options = useMemo(() => {
                if (!people || !Array.isArray(people)) {
                    return [];
                }
                return people.map((person) => {
                    const title = person.title && person.title.rendered ? person.title.rendered : person.title;
                    return {
                        value: person.id,
                        label: title,
                    };
                });
            }, [people]);

            const selected = useMemo(() => {
                return options.find((o) => o.value === personId);
            }, [options, personId]);

            const blockProps = useBlockProps({ className: 'person-card-block-editor' });

            return el(
                'div',
                blockProps,
                [
                    el(
                        InspectorControls,
                        null,
                        el(
                            PanelBody,
                            { title: __('Person Card Settings', 'regen-wp'), initialOpen: true },
                            [
                                el(ComboboxControl, {
                                    label: __('Person', 'regen-wp'),
                                    value: personId || '',
                                    onChange: (val) => {
                                        const nextVal = val ? parseInt(val, 10) : undefined;
                                        setAttributes({ personId: nextVal });
                                    },
                                    options,
                                    allowReset: true,
                                    placeholder: isResolving
                                        ? __('Loading people…', 'regen-wp')
                                        : __('Search people', 'regen-wp'),
                                }),
                                el(SelectControl, {
                                    label: __('Style', 'regen-wp'),
                                    value: variant || 'about',
                                    options: variantOptions,
                                    onChange: (val) => setAttributes({ variant: val || 'about' }),
                                }),
                            ]
                        )
                    ),
                    isResolving && el(Spinner, {}),
                    !personId && !isResolving &&
                    el('p', { className: 'person-card-preview' }, __('Select a person in the sidebar.', 'regen-wp')),
                    personId && selected &&
                    el('p', { className: 'person-card-preview' }, `${selected.label} — ${variant === 'resident' ? __('Resident style', 'regen-wp') : __('About style', 'regen-wp')}`),
                    personId && !selected && !isResolving &&
                    el('p', { className: 'person-card-preview' }, __('Person not found (maybe unpublished or trashed).', 'regen-wp')),
                    el('p', { className: 'person-card-preview' }, __('Front-end renders server-side; update to see full markup.', 'regen-wp')),
                ]
            );
        },
        save: () => null,
    });
})(window.wp.blocks, window.wp.element, window.wp.components, window.wp.data, window.wp.blockEditor, window.wp.i18n);
