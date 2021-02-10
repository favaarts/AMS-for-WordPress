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
    title: i18n.__('AMS Programs', 'amsnetwork-gutenbergevent-block'),
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
     eventshowbutton: {
      type: 'boolean',
      default: true
     },
     displaypastevents: {
      type: 'boolean',
      default: true
     },
     tagsevents: {
      type: 'boolean',
      default: true
     },
     organizationevents: {
      type: 'boolean',
      default: false
     },
    register_url: {
        type: 'string',
    },
    register_urltab: {
      type: 'string',
      default: '_self',
    },
    showhideurl: {
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
            el('p', {}, i18n.__('Add custom meta options for programs.', 'amsnetwork-gutenbergevent-block')),
            el(ToggleControl, {
              label: 'Sidebar',
              onChange: ( value ) => {
                 props.setAttributes( { eventsidebar: value } );
              },
              checked: props.attributes.eventsidebar,
            }),
            el('p', {}, i18n.__('Show hide view more button.', 'amsnetwork-gutenbergevent-block')),
            el(ToggleControl, {
              label: 'View More',
              onChange: ( value ) => {
                 props.setAttributes( { eventshowbutton: value } );
              },
              checked: props.attributes.eventshowbutton,
            }),
            el('p', {}, i18n.__('( On / Off ) Display past events.', 'amsnetwork-gutenbergevent-block')),
            el(ToggleControl, {
              label: 'Display Past Events',
              onChange: ( value ) => {
                 props.setAttributes( { displaypastevents: value } );
              },
              checked: props.attributes.displaypastevents,
            }),
            el('p', {}, i18n.__('( On / Off ) Display Labels.', 'amsnetwork-gutenbergevent-block')),
            el(ToggleControl, {
              label: 'Labels',
              onChange: ( value ) => {
                 props.setAttributes( { tagsevents: value } );
              },
              checked: props.attributes.tagsevents,
            }),
            el('p', {}, i18n.__('( On / Off ) Display Organizations.', 'amsnetwork-gutenbergevent-block')),
            el(ToggleControl, {
              label: 'Organizations',
              onChange: ( value ) => {
                 props.setAttributes( { organizationevents: value } );
              },
              checked: props.attributes.organizationevents,
            }),
            el( TextControl,
              {
                label: '(Optional) Registration URL',
                onChange: ( value ) => {
                  props.setAttributes( { register_url: value } );
                },
                value: props.attributes.register_url
              }
            ),
            el( SelectControl,
              {
                label: 'Where to open the Registration URL',
                //help: 'Some kind of description',
                options : [
                  { label: 'Same Tab', value: '_self' },
                  { label: 'New Tab', value: '_blank' },
                ],
                onChange: ( value ) => {
                  props.setAttributes( { register_urltab: value } );
                },
                value: props.attributes.register_urltab
              }
            ),
            el('p', {}, i18n.__('( On / Off ) Show Hide register button.', 'amsnetwork-gutenbergevent-block')),
            el(ToggleControl, {
              label: 'Display Register Button',
              onChange: ( value ) => {
                 props.setAttributes( { showhideurl: value } );
              },
              checked: props.attributes.showhideurl,
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
            el( SelectControl,
              {
                label: 'Number of programs display in this page.',
                //help: 'Some kind of description',
                options : [
                  { label: '1', value: '1' },
                  { label: '2', value: '2' },
                  { label: '3', value: '3' },
                  { label: '4', value: '4' },
                  { label: '5', value: '5' },
                  { label: '6', value: '6' },
                  { label: '7', value: '7' },
                  { label: '8', value: '8' },
                  { label: '9', value: '9' },
                  { label: '10', value: '10' },
                  { label: '11', value: '11' },
                  { label: '12', value: '12' },
                ],
                onChange: ( value ) => {
                  props.setAttributes( { event_pagination: value } );
                },
                value: props.attributes.event_pagination
              }
            ),
            el('p', {}, i18n.__('Hide show programs price.', 'amsnetwork-gutenbergevent-block')),
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
           el( 'input', { 'type': 'hidden', 'name' : 'eventshowbutton', 'value' : ( props.attributes.eventshowbutton == true ? 'yes' : 'no') } ),
           el( 'input', { 'type': 'hidden', 'name' : 'displaypastevents', 'value' : ( props.attributes.displaypastevents == true ? 'yes' : 'no') } ),
           el( 'input', { 'type': 'hidden', 'name' : 'organizationevents', 'value' : ( props.attributes.organizationevents == true ? 'yes' : 'no') } ),
           el( 'input', { 'type': 'hidden', 'name' : 'register_url', 'value' : ( props.attributes.register_url) } ),
           el( 'input', { 'type': 'hidden', 'name' : 'register_urltab', 'value' : ( props.attributes.register_urltab) } ), 
           el( 'input', { 'type': 'hidden', 'name' : 'showhideurl', 'value' : ( props.attributes.showhideurl) } ),            
           el( 'input', { 'type': 'hidden', 'name' : 'tagsevents', 'value' : ( props.attributes.tagsevents == true ? 'yes' : 'no') } ),            
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