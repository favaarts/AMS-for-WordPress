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

  registerBlockType('wpdams-amsnetwork-project/amsnetwork-block-project', {
    title: i18n.__('AMS Projects', 'amsnetwork-gutenbergproject-block'),
    description: i18n.__('AMS network block setting', 'amsnetwork-gutenbergproject-block'),
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
      eventsidebar: {
      type: 'boolean',
      default: true
     },
     eventshowbutton: {
      type: 'boolean',
      default: true
     },
     radio_attr_event: {
      type: 'string',
      default: 'three_col',
    },
    
     type: { type: 'string', default: 'amsproject' },
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
              title: i18n.__('Block Content', 'amsnetwork-gutenbergproject-block'),
              className: 'block-content',
              initialOpen: true
            },
            el('p', {}, i18n.__('Add custom meta options for programs.', 'amsnetwork-gutenbergproject-block')),
            el(ToggleControl, {
              label: 'Sidebar',
              onChange: ( value ) => {
                 props.setAttributes( { eventsidebar: value } );
              },
              checked: props.attributes.eventsidebar,
            }),
            el('p', {}, i18n.__('Show hide view more button.', 'amsnetwork-gutenbergproject-block')),
            el(ToggleControl, {
              label: 'View More',
              onChange: ( value ) => {
                 props.setAttributes( { eventshowbutton: value } );
              },
              checked: props.attributes.eventshowbutton,
            }),
            el( RadioControl,
              {
                label: 'Grid Layout',
                //help: 'Some kind of description',
                options : [
                  { label: 'Two Column', value: 'two_col' },
                  { label: 'Three Column', value: 'three_col' },
                  { label: 'Four Column', value: 'four_col' },
                  { label: 'List View', value: 'list_view' },
                ],
                onChange: ( value ) => {
                  props.setAttributes( { radio_attr_event: value } );
                },
                selected: props.attributes.radio_attr_event
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
           el( 'input', { 'type': 'hidden', 'name' : 'eventsidebar', 'value' : ( props.attributes.eventsidebar == true ? 'yes' : 'no') } ),
           el( 'input', { 'type': 'hidden', 'name' : 'eventshowbutton', 'value' : ( props.attributes.eventshowbutton == true ? 'yes' : 'no') } ),
           el( 'input', { 'type': 'hidden', 'name' : 'radio_attr_event', 'value' : ( props.attributes.radio_attr_event) } ),
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