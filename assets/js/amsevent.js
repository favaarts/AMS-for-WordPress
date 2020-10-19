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
      eventsidebar: {
      type: 'boolean',
      default: true
     },
     radio_attr_event: {
      type: 'string',
      default: 'three_col',
    },
    event_pagination: {
      type: 'string',
      default: '8',
    },
    member: {
      type: 'boolean',
      default: true
    },
    nonmember: {
      type: 'boolean',
      default: true
    },
    earlybird: {
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
            el('p', {}, i18n.__('Add custom meta options for events.', 'amsnetwork-gutenbergevent-block')),
            el(ToggleControl, {
              label: 'Sidebar',
              onChange: ( value ) => {
                 props.setAttributes( { eventsidebar: value } );
              },
              checked: props.attributes.eventsidebar,
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
            el('p', {}, i18n.__('Hide show event price.', 'amsnetwork-gutenbergevent-block')),
            el(ToggleControl, {
              label: 'Member Enrollment Price',
              onChange: ( value ) => {
                 props.setAttributes( { member: value } );
              },
              checked: props.attributes.member,
            }),
            el(ToggleControl, {
              label: 'Non Member Price',
              onChange: ( value ) => {
                 props.setAttributes( { nonmember: value } );
              },
              checked: props.attributes.nonmember,
            }),
            el(ToggleControl, {
              label: 'Earlybird Discount',
              onChange: ( value ) => {
                 props.setAttributes( { earlybird: value } );
              },
              checked: props.attributes.earlybird,
            }),
            
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
           el( 'input', { 'type': 'hidden', 'name' : 'radio_attr_event', 'value' : ( props.attributes.radio_attr_event) } ),
           el( 'input', { 'type': 'hidden', 'name' : 'event_pagination', 'value' : ( props.attributes.event_pagination) } ),
           el( 'input', { 'type': 'hidden', 'name' : 'member', 'value' : ( props.attributes.member == true ? 'yes' : 'no') } ),
           el( 'input', { 'type': 'hidden', 'name' : 'nonmember', 'value' : ( props.attributes.nonmember == true ? 'yes' : 'no') } ),
           el( 'input', { 'type': 'hidden', 'name' : 'earlybird', 'value' : ( props.attributes.earlybird == true ? 'yes' : 'no') } ),
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