// Call the dataTables jQuery plugin
$(document).ready(function () {
  $("#dataTable").DataTable();
});
new DataTable("#asset", {
  fixedHeader: { header: true },
  columnDefs: [
    {
      targets: [1, 2],
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
new DataTable("#assetUser", {
  fixedHeader: { header: true },
  columnDefs: [
    {
      targets: [0, 2],
      columnControl: [
        "order",
        ["search", "spacer", "orderAsc", "orderDesc", "orderClear"],
      ],
    },
    {
      targets: [1],
      columnControl: [
        "order",
        ["searchList","spacer", "orderAsc", "orderDesc", "orderClear"],
      ],
    },
  ],
  ordering: {
    indicators: false,
    handler: false,
  },
});
new DataTable("#account", {
  fixedHeader: { header: true },
  columnDefs: [
    {
      targets: [1, 2],
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
  fixedHeader: { header: true },
  columnDefs: [
    {
      targets: [1, 2, 3, 4, 5],
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
new DataTable("#tickets", {
  fixedHeader: { header: true },
  columnDefs: [
    {
      targets: [3, 4, 6],
      columnControl: [
        "order",
        ["searchList", "spacer", "orderAsc", "orderDesc", "orderClear"],
      ],
    },
    {
      targets: [1,2,7],
      columnControl: [
        "order",
        ["search"],
      ],
    },
  ],
  ordering: {
    indicators: false,
    handler: false,
  },
});
new DataTable("#asset-ticket", {
  fixedHeader: { header: true },
  columnDefs: [
    {
      targets: [2, 3],
      columnControl: [
        "order",
        ["searchList", "spacer", "orderAsc", "orderDesc", "orderClear"],
      ],
    },
    {
      targets: [0,1],
      columnControl: [
        "order",
        ["search"],
      ],
    },
  ],
  ordering: {
    indicators: false,
    handler: false,
  },
});

new DataTable();
