- Recurring shipment plan WooCommerce extension

- Accept cash on on delivery
- Create a new product type call “Recurring Plan”
- Add new meta fields area under general tab for recurring plan product type
    - Plan starting price
    - Checkbox to let buyers to pause or resume plan (“Allow buyers to pause/resume plan”). If checked,
        . Show a text fields to add number of days buyers are allowed to pause the plan before their next recurring shipment. Field name: Plan halt grace period
    - Text fields to add number of days buyers are allowed to switch to a different plan before their next shipment. Field name: Switching grace period 
    - Dropdown to select how many days (options: Create on the same day, Day before, Three days before, Week before) before an order should be created for next shipment from the next recurring product shipment date. Fields name: Recurring shipment order day
    - Heading “Welcome product details”
    - Welcome product name field
    - Welcome product image (upload or select from media library) 
    - Welcome product short description field 
    - Welcome product SKU field
- Create new tab call “Recurring plans”
    - Values textfield that generate new recurring product plans when saved with given values separating using “|” (minimum one value, maximum five values)
    - Dropdown with product plans to select default plan when buyer purchase the product. If recurring plans are not created, show “Your haven’t created any recurring plans yet”
    - Heading “Recurring product plans”
    - Sections with plan name subheading / if If recurring plans are not created, show “Your haven’t created any recurring plans yet”
    - Each section has following
        . Plan short description
        . Plan recurring product name field
        . Plan recurring product image (upload or select from media library)
        . Plan recurring product SKU field
        . Plan recurring interval field (Dropdown with options: every week, every two weeks, every month, every two months, every three months, every four months, every six months)
        . Recurring price field
    - Button to delete each plan.
- Save new meta fields.
- Create new orders for the buyers’ recurring shipment before the given day value from the “Recurring shipment order day” field.
- Send new order emails when orders are created with recurring product plan data. 
- Display the next shipment date for the current recurring product plan with a shortcode in frontend.
- Shortcode to display switch recurring plan options who has active recurring plan: Display radio button list with available recurring plans, current active plan “checked” and save button. When saved, validate the switch request and handle any necessary changes to the recurring product and shipping schedule.
