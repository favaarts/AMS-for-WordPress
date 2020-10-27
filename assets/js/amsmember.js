(function(blocks, editor, components, i18n, element) {
    var el = element.createElement;
    var registerBlockType = blocks.registerBlockType;
    var InspectorControls = editor.InspectorControls;
  
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
        alignment: {
          type: 'string',
          default: 'center'
        }
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
              el('p', {}, i18n.__('Add custom meta options to show or hide sidebar', 'amsnetwork-gutenbergmember-block')),
            )
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
        return el('div', null, '['+props.attributes.type+']')
      }
    })
  })(
    window.wp.blocks,
    window.wp.editor,
    window.wp.components,
    window.wp.i18n,
    window.wp.element
  );
