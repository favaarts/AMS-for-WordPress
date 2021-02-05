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
      projectsidebar: {
      type: 'boolean',
      default: true
     },
     radio_attr_project: {
      type: 'string',
      default: 'three_col',
    },
    project_pagination: {
      type: 'string',
      default: '8',
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
                 props.setAttributes( { projectsidebar: value } );
              },
              checked: props.attributes.projectsidebar,
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
                  props.setAttributes( { radio_attr_project: value } );
                },
                selected: props.attributes.radio_attr_project
              }
            ),
            el( SelectControl,
              {
                label: 'Number of project display in this page.',
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
                  props.setAttributes( { project_pagination: value } );
                },
                value: props.attributes.project_pagination
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
           el( 'input', { 'type': 'hidden', 'name' : 'projectsidebar', 'value' : ( props.attributes.projectsidebar == true ? 'yes' : 'no') } ),
           el( 'input', { 'type': 'hidden', 'name' : 'radio_attr_project', 'value' : ( props.attributes.radio_attr_project) } ),
           el( 'input', { 'type': 'hidden', 'name' : 'project_pagination', 'value' : ( props.attributes.project_pagination) } ),
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