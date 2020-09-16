(function(blocks, editor, components, i18n, element) {
  var el = element.createElement;
  var registerBlockType = blocks.registerBlockType;
  var RichText = editor.RichText;
  var BlockControls = editor.BlockControls;
  var AlignmentToolbar = editor.AlignmentToolbar;
  var MediaUpload = editor.MediaUpload;
  var InspectorControls = editor.InspectorControls;
  var TextControl = components.TextControl;
  var ToggleControl = components.ToggleControl;
  registerBlockType('wpdams-amsnetwork/amsnetwork-block', {
    title: i18n.__('AMS Assets', 'amsnetwork-gutenberg-block'),
    description: i18n.__('AMS network block setting', 'amsnetwork-gutenberg-block'),
    icon: 'screenoptions',
    category: 'common',
    attributes: {
      mediaID: {
        type: 'number'
      },
      mediaURL: {
        type: 'string',
        source: 'attribute',
        selector: 'img',
        attribute: 'src'
      },
      title: {
        type: 'text',
        selector: 'h3'
      },
      text: {
        type: 'text',
        selector: 'p'
      },
      buttonText: {
        type: 'text'
      },
      buttonURL: {
        type: 'url'
      },
      searchoption: {
      type: 'boolean',
      default: true
     },
     categoryoption: {
      type: 'boolean',
      default: true
     },
     equipmentoption: {
      type: 'boolean',
      default: true
     },
     type: { type: 'string', default: 'amscategoryequipment' },
      alignment: {
        type: 'string',
        default: 'center'
      }
    },
    edit: function(props) {

     function updateContent( newdata ) {
            props.setAttributes( { content: newdata } );
         }  

      var attributes = props.attributes;
      var onSelectImage = function(media) {
        return props.setAttributes({
          mediaURL: media.url,
          mediaID: media.id
        })
      };
      return [
        
        el(InspectorControls, {
            key: 'inspector'
          },
          el(components.PanelBody, {
              title: i18n.__('Block Content', 'amsnetwork-gutenberg-block'),
              className: 'block-content',
              initialOpen: true
            },
            el('p', {}, i18n.__('Add custom meta options to your block', 'amsnetwork-gutenberg-block')),
            
            el(ToggleControl, {
              label: 'Search',
            onChange: ( value ) => {
               props.setAttributes( { searchoption: value } );
            },
            checked: props.attributes.searchoption,
            }),
            el(ToggleControl, {
              label: 'Category',
            onChange: ( value ) => {
               props.setAttributes( { categoryoption: value } );
            },
            checked: props.attributes.categoryoption,
            }),
            el(ToggleControl, {
              label: 'Equipment',
            onChange: ( value ) => {
               props.setAttributes( { equipmentoption: value } );
            },
            checked: props.attributes.equipmentoption,
            })
          )
        ),
        el( 'div',
            {
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
      return (
        el( 'div',
            {
               className: 'amsblock-box amsblock-' + props.attributes.type
           },
           el(
               'h4',
               null,
               props.attributes.title
           ),
           el('div', {className: 'header-right-part wp-block-shortcode'},
                           el( wp.element.RawHTML, null, '['+props.attributes.type+']')
           ),
           el( 'input', { 'type': 'hidden', 'name' : 'search_option_in', 'value' : ( props.attributes.searchoption == true ? 'yes' : 'no' ) } ),
           el( 'input', { 'type': 'hidden', 'name' : 'category_option_in', 'value' : ( props.attributes.categoryoption == true ? 'yes' : 'no' ) } ),
           el( 'input', { 'type': 'hidden', 'name' : 'equipment_option_in', 'value' : ( props.attributes.equipmentoption == true ? 'yes' : 'no' ) } ),

         )

      )
    }
  })
})(
  window.wp.blocks,
  window.wp.editor,
  window.wp.components,
  window.wp.i18n,
  window.wp.element
);