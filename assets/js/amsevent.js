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
  var SelectControl = components.SelectControl;

  registerBlockType('wpdams-amsnetwork-event/amsnetwork-block-event', {
    title: i18n.__('AMS Events', 'amsnetwork-gutenbergevent-block'),
    description: i18n.__('AMS network block setting', 'amsnetwork-gutenbergevent-block'),
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
     radio_attr_event: {
      type: 'string',
      default: 'three_col',
    },
    event_pagination: {
      type: 'string',
      default: '8',
    },
     categoryoption: {
      type: 'boolean',
      default: true
     },
     equipmentoption: {
      type: 'boolean',
      default: true
     },
     type: { type: 'string', default: 'amseventlisting' },
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
              title: i18n.__('Block Content', 'amsnetwork-gutenbergevent-block'),
              className: 'block-content',
              initialOpen: true
            },
            el('p', {}, i18n.__('Add custom meta options to show or hide sidebar', 'amsnetwork-gutenbergevent-block')),
            
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
                  props.setAttributes( { radio_attr_event: value } );
                },
                selected: props.attributes.radio_attr_event
              }
            ),

            el( SelectControl,
              {
                label: 'Number of events display in this page.',
                //help: 'Some kind of description',
                options : [
                  { label: '6', value: '6' },
                  { label: '8', value: '8' },
                  { label: '9', value: '9' },
                  { label: '10', value: '10' },
                ],
                onChange: ( value ) => {
                  props.setAttributes( { event_pagination: value } );
                },
                selected: props.attributes.event_pagination
              }
            ),
            
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
           el( 'input', { 'type': 'hidden', 'name' : 'radio_attr_event', 'value' : ( props.attributes.radio_attr_event) } ),
           el( 'input', { 'type': 'hidden', 'name' : 'event_pagination', 'value' : ( props.attributes.event_pagination) } ),

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