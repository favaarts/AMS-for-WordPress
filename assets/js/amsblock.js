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
     externallink: {
      type: 'boolean',
      default: true
     },
     includedacc: {
      type: 'boolean',
      default: true
     },
     warrantyinfo: {
      type: 'boolean',
      default: true
     },
    register_assets_url: {
        type: 'string',
    },
    register_assets_urltab: {
      type: 'string',
      default: '_self',
    },
    showhidebookurl: {
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
    member: {
      type: 'boolean',
      default: true
    },
    nonmember: {
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
            el('p', {}, i18n.__('Asset Details custom options to show or hide the corresponding content', 'amsnetwork-gutenberg-block')),
            
            el(ToggleControl, {
              label: 'External Resources Link',
              onChange: ( value ) => {
                 props.setAttributes( { externallink: value } );
              },
              checked: props.attributes.externallink,
            }),
            el(ToggleControl, {
              label: 'Included accessories',
              onChange: ( value ) => {
                 props.setAttributes( { includedacc: value } );
              },
              checked: props.attributes.includedacc,
            }),
            el(ToggleControl, {
              label: 'Warranty Information',
              onChange: ( value ) => {
                 props.setAttributes( { warrantyinfo: value } );
              },
              checked: props.attributes.warrantyinfo,
            }),
            el( TextControl,
              {
                label: '(Optional) Booking URL',
                onChange: ( value ) => {
                  props.setAttributes( { register_assets_url: value } );
                },
                value: props.attributes.register_assets_url
              }
            ),
            el( SelectControl,
              {
                label: 'Where to open the Booking URL',
                //help: 'Some kind of description',
                options : [
                  { label: 'Same Tab', value: '_self' },
                  { label: 'New Tab', value: '_blank' },
                ],
                onChange: ( value ) => {
                  props.setAttributes( { register_assets_urltab: value } );
                },
                value: props.attributes.register_assets_urltab
              }
            ),
            el('p', {}, i18n.__('( On / Off ) Show Hide booking button.', 'amsnetwork-gutenbergevent-block')),
            el(ToggleControl, {
              label: 'Display Booking Button',
              onChange: ( value ) => {
                 props.setAttributes( { showhidebookurl: value } );
              },
              checked: props.attributes.showhidebookurl,
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
                  props.setAttributes( { radio_attr: value } );
                },
                selected: props.attributes.radio_attr
              }
            ),
            el( TextControl,
              {
                label: 'All Items',
                onChange: ( value ) => {
                  props.setAttributes( { all_items_url: value } );
                },
                value: props.attributes.all_items_url
              }
            ),
            el('p', {}, i18n.__('Hide show assets price.', 'amsnetwork-gutenbergevent-block')),
            el(ToggleControl, {
              label: 'Member Price',
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
           el( 'input', { 'type': 'hidden', 'name' : 'sidebar_option_in', 'value' : ( props.attributes.externallink == true ? 'yes' : 'no' ) } ),
           el( 'input', { 'type': 'hidden', 'name' : 'sidebar_option_in', 'value' : ( props.attributes.includedacc == true ? 'yes' : 'no' ) } ),
           el( 'input', { 'type': 'hidden', 'name' : 'sidebar_option_in', 'value' : ( props.attributes.warrantyinfo == true ? 'yes' : 'no' ) } ),
           el( 'input', { 'type': 'hidden', 'name' : 'radio_attr', 'value' : ( props.attributes.radio_attr) } ),
           el( 'input', { 'type': 'hidden', 'name' : 'register_assets_url', 'value' : ( props.attributes.register_assets_url) } ),
           el( 'input', { 'type': 'hidden', 'name' : 'register_assets_urltab', 'value' : ( props.attributes.register_assets_urltab) } ),
           el( 'input', { 'type': 'hidden', 'name' : 'showhidebookurl', 'value' : ( props.attributes.showhidebookurl) } ),
           el( 'input', { 'type': 'hidden', 'name' : 'all_items_url', 'value' : ( props.attributes.all_items_url) } ),
           el( 'input', { 'type': 'hidden', 'name' : 'member', 'value' : ( props.attributes.member == true ? 'yes' : 'no') } ),
           el( 'input', { 'type': 'hidden', 'name' : 'nonmember', 'value' : ( props.attributes.nonmember == true ? 'yes' : 'no') } ),
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