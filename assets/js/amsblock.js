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
  var RadioControl = components.RadioControl;

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
      sidebaroption: {
      type: 'boolean',
      default: true
     },
     all_items_url: {
        type: 'string',
     },
     radio_attr: {
      type: 'string',
      default: 'three_col',
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

      var radioField = props.attributes;
      function onChangeRadioField( newValue ) {
        props.setAttributes( { radioField: newValue } );
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
            el('p', {}, i18n.__('Add custom meta options to show or hide sidebar', 'amsnetwork-gutenberg-block')),
            
            el(ToggleControl, {
              label: 'Sidebar',
              onChange: ( value ) => {
                 props.setAttributes( { sidebaroption: value } );
              },
              checked: props.attributes.sidebaroption,
            }),
            el( RadioControl,
              {
                label: 'Grid Layout',
                //help: 'Some kind of description',
                options : [
                  { label: 'Two Column', value: 'two_col' },
                  { label: 'Three Column', value: 'three_col' },
                  { label: 'Four Column', value: 'four_col' },
                ],
                onChange: ( value ) => {
                  props.setAttributes( { radio_attr: value } );
                },
                selected: props.attributes.radio_attr
              }
            ),
            el( TextControl,
              {
                label: 'All Items URL',
                onChange: ( value ) => {
                  props.setAttributes( { all_items_url: value } );
                },
                value: props.attributes.all_items_url
              }
            )
            //
            
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
           el( 'input', { 'type': 'hidden', 'name' : 'sidebar_option_in', 'value' : ( props.attributes.sidebaroption == true ? 'yes' : 'no' ) } ),
           el( 'input', { 'type': 'hidden', 'name' : 'radio_attr', 'value' : ( props.attributes.radio_attr) } ),
           el( 'input', { 'type': 'hidden', 'name' : 'all_items_url', 'value' : ( props.attributes.all_items_url) } ),
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