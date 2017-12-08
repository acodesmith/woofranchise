<?php
/**
 * @var array $field
 * @var array $post_meta
 */

if( empty( $field ) )
        return;

$saved_value = isset( $post_meta[ $field['meta_key'] ] ) ? $post_meta[ $field['meta_key'] ][0] : null;

$field['attributes'] = isset( $field['attributes'] ) ? $field['attributes'] : [];
$attr_str = [];

foreach( $field['attributes'] as $name => $value )
    $attr_str[] = "$name='$value'";
?>
<div class="wf_meta_box_field wf_meta_box_field_<?= $field['type']; ?>">
    <?php switch( $field['type'] ):
        case 'text':
        case 'time':
        case 'number': ?>
                <label for="<?= $field['id']; ?>"><?= $field['name']; ?></label>
                <input
                    id="<?= $field['id']; ?>"
                    name="<?= $field['meta_key']; ?>"
                    class="regular-text"
                    type="<?= $field['type']; ?>"
                    <?= $saved_value ? "value='$saved_value'" : ''; ?>
                    <?= implode( ' ', $attr_str ); ?>
                >
            <?php break;
	    case 'select': ?>
		    <label for="<?= $field['id']; ?>"><?= $field['name']; ?></label>
		    <select
				    id="<?= $field['id']; ?>"
				    name="<?= $field['meta_key']; ?>"
				    class="regular-text regular-select"
			    <?= implode( ' ', $attr_str ); ?>
		    >
			    <?php if( ! empty( $field['options'] ) ):
				        foreach( $field['options'] as $value => $option ): ?>
					        <option
						        value="<?= $value; ?>"
						        <?= ! empty( $saved_value ) && $value == $saved_value ? 'selected="selected"' : null; ?>
					        ><?= $option; ?></option>
				        <?php endforeach;;
			        endif; ?>
		    </select>
	    	<?php break;
    endswitch; ?>
</div>