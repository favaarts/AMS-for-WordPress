(function(blocks, editor, components, i18n, element) {
    var el = element.createElement;
    var registerBlockType = blocks.registerBlockType;
    var InspectorControls = editor.InspectorControls;
    var RadioControl = components.RadioControl;
    var TextControl = components.TextControl;
    var ToggleControl = components.ToggleControl;
  
    registerBlockType('wpdams-amsnetwork-member/amsnetwork-block-member', {
      title: i18n.__('AMS Members', 'amsnetwork-gutenbergmember-block'),
      description: i18n.__('AMS network block setting', 'amsnetwork-gutenbergmember-block'),
      icon: 'screenoptions',
      category: 'common',
      attributes: {
        id: {
          type: 'number',
          default: 1
        },
        type: {
          type: 'string',
          default: 'members_list'
        },
        memberconnecttoprojectid: {
          type: 'string',
        },
        membertoproject: {
          type: 'boolean',
          default: false
        },
        alignment: {
          type: 'string',
          default: 'center'
        },
        layout_type: {
          type: 'string',
          default: 'four_col',
        },
      },
      edit: function(props) {
         
        function updateContent( newdata ) {
          props.setAttributes( { content: newdata } );
        }  
        return [
          el(InspectorControls, {
              key: 'inspector'
            },
            el(components.PanelBody, {
                title: i18n.__('Block Content', 'amsnetwork-gutenbergmember-block'),
                className: 'block-content',
                initialOpen: true
              },
              el('p', {}, i18n.__('Add project page ID, connect to project.', 'amsnetwork-gutenbergproject-block')),
              el( TextControl,
                {
                  label: 'Project page ID',
                  onChange: ( value ) => {
                    props.setAttributes( { memberconnecttoprojectid: value } );
                  },
                  value: props.attributes.memberconnecttoprojectid,
                }
              ),
              el('p', {}, i18n.__('Add custom meta options to show or hide sidebar', 'amsnetwork-gutenbergmember-block')),
              el(ToggleControl, {
                label: 'Connect Members to Projects',
                onChange: ( value ) => {
                   props.setAttributes( { membertoproject: value } );
                },
                checked: props.attributes.membertoproject,
              }),              
              el(RadioControl, {
                label: 'Grid Layout',
                //help: 'Some kind of description',
                options : [
                  { label: 'Two Column', value: 'two_col' },
                  { label: 'Three Column', value: 'three' },
                  { label: 'Four Column', value: 'four_col' },
                  { label: 'List View', value: 'list_view' },
                ],
                onChange: ( value ) => {
                  props.setAttributes( { layout_type: value } );
                },
                selected: props.attributes.layout_type
              }),
            ),
          ),
          el( 'div', {
            className: 'amsblock-box amsblock-' + props.attributes.type
            },
            el(
                wp.editor.RichText,
                {
                  tagName: 'p',
                  onChange: updateContent,
                  value: '['+props.attributes.type+']'
                }
            )
          ),
        ];
      },
      save: function(props) {
        var attributes = props.attributes;
        return el(
          'div', {className: props.attributes.type},
          el('div', null, '['+props.attributes.type+']'),
          el( 'input', { 'type': 'hidden', 'name' : 'layout_type', 'value' : props.attributes.layout_type } ),
          el( 'input', { 'type': 'hidden', 'name' : 'membertoproject', 'value' : ( props.attributes.membertoproject == true ? 'yes' : 'no') } ),
          el( 'input', { 'type': 'hidden', 'name' : 'memberconnecttoprojectid', 'value' : ( props.attributes.memberconnecttoprojectid) } ),          
        );
      }
    })
  })(
    window.wp.blocks,
    window.wp.editor,
    window.wp.components,
    window.wp.i18n,
    window.wp.element
  );
