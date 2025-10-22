// Call the dataTables jQuery plugin
$(document).ready(function () {
  $("#dataTable").DataTable();
});
new DataTable("#asset", {
  fixedHeader: { header: true },
  columnDefs: [
    {
      targets: [1, 2, 3, 4, 5], // apply to columns you want
      columnControl: [
        "order",
        ["searchList", "spacer", "orderAsc", "orderDesc", "orderClear"],
      ],
    },
  ],
  ordering: {
    indicators: false,
    handler: false,
  },
});


//ColumnControl for Aseset Inventory
new DataTable("#asset_inventory", {
  columnControl: [
    "order",
    ["search", "spacer", "orderAsc", "orderDesc", "orderClear"],
  ],
  columnDefs: [
    {
      targets: [0],
      columnControl: [
        "order",
        ["searchList", "spacer", "orderAsc", "orderDesc", "orderClear"],
      ],
    },
  ],
  ordering: {
    indicators: false,
    handler: false,
  },
});

new DataTable();
