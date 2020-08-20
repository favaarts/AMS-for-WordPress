var el = wp.element.createElement;

wp.blocks.registerBlockType('wpdams-amsnetwork/amsnetwork-block', {

   title: 'AMS', // Block name visible to user

   icon: 'screenoptions', // Toolbar icon can be either using WP Dashicons or custom SVG

   description: 'AMS network block setting',

   category: 'common', // Under which category the block would appear

   attributes: { // The data this block will be storing

      type: { type: 'string', default: 'default' }, // Notice box type for loading the appropriate CSS class. Default class is 'default'.

      title: { type: 'string' }, // Notice box title in h4 tag

      content: { type: 'array', source: 'children', selector: 'p' } /// Notice box content in p tag

   },

   edit: function(props) {
      // How our block renders in the editor in edit mode
         function updateTitle( event ) {
            props.setAttributes( { title: event.target.value } );
         }

         function updateContent( newdata ) {
            props.setAttributes( { content: newdata } );
         }

         function updateType( event ) {
            props.setAttributes( { type: event.target.value } );
         }

         return el( 'div',
            {
               className: 'amsblock-box amsblock-' + props.attributes.type
            },
            el(
               'select',
               {
                  onChange: updateType,
                  value: props.attributes.type,
               },
               el("option", {value: "" }, "Select"),
               el("option", {value: "amscategory" }, "Filter"),
               el("option", {value: "amsequipment" }, "Equipment"),
               el("option", {value: "amscategoryequipment" }, "Category Equipment")
            ),
            /*el(
               wp.editor.RichText,
               {
                  type: 'p',
                  value: props.attributes.title,
                  onChange: updateTitle,
               }
            ),*/
            el(
               wp.editor.RichText,
               {
                  tagName: 'p',
                  onChange: updateContent,
                  value: '['+props.attributes.type+']'
               }
            )

         ); // End return
         //React.createElement("p", null, '['+props.attributes.type+']');
         /*React.createElement("p", {
           onChange: "updateContent"
         }, '['+props.attributes.type+']');*/
   },

   save: function(props) {
      // How our block renders on the frontend
      return el( 'div',
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
                    )

      ); // End return
   }
});