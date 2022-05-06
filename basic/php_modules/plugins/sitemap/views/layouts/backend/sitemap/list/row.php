<tr class="item_parent border-0">
    <td class="td_name align-middle py-1">
        <?php echo $this->item['title']; ?>
    </td>
    <td class="align-middle py-2">
        <?php echo $this->item['plugin']; ?>
    </td>
    <td class="align-middle py-2">
        <?php echo $this->item['slug']; ?>
    </td>
    <td class="align-middle py-2" style="font-size: 13px;">
    <?php 
        if (is_array($this->item['permission']) && $this->item['permission'])
        {
            foreach($this->item['permission'] as $item)
            {
                echo '<span class="cate my-1 block">'. $item. '</span>';
            }
        }
        else
        {
            echo '__';
        }
    ?>
    </td>
    <td class="align-middle py-2">
        <?php echo $this->item['object'] ? $this->item['object'] : '__'; ?>
    </td>
    <td class="align-middle py-2">
        <?php echo $this->item['object_id'] ? $this->item['object_id'] : '__'; ?>
    </td>
    <td class="align-middle py-2">
        <?php echo $this->item['published'] ? 'Active' : 'Block'; ?>
    </td>
    <td class="align-middle py-2">
        <?php echo $this->item['language'] == 'vi' ? 'Vietnamese' : 'English'; ?>
    </td>
</tr>