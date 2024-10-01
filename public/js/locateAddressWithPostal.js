// Initialize Radar with the project key
Radar.initialize("prj_live_pk_282bce66618b63742b0ad59cd6a9c2deda92aadd");

// Ensure the DOM element exists before initializing
document.addEventListener("DOMContentLoaded", () => {
    const initializeAutocomplete = (
        containerId,
        cityInputId,
        stateInputId,
        codeInputId // Include code input for postal code
    ) => {
        const container = document.getElementById(containerId);
        const cityInput = document.getElementById(cityInputId);
        const stateInput = document.getElementById(stateInputId);
        const codeInput = document.getElementById(codeInputId);

        if (container) {
            Radar.ui.autocomplete({
                container: containerId,
                width: "600px", // Set desired width
                maxHeight: "600px", // Set max height of the results box
                placeholder: "Search address",
                limit: 8, // Limit the number of results to show
                minCharacters: 3, // Minimum characters before showing suggestions
                near: null, // Use default IP-based location
                debounceMS: 200, // Wait time before fetching results
                responsive: true, // Make the input responsive
                layers: ["postalCode"], // Include relevant layers

                onResults: (addresses) => {
                    // Filter addresses that have postal codes
                    const filteredAddresses = addresses.filter(
                        (address) => address.postalCode
                    );

                    // Log the filtered results
                    console.log(
                        "Filtered Results (with postalCode):",
                        filteredAddresses
                    );

                    // Optional: You can manipulate the UI here based on filteredAddresses
                },
                onSelection: (address) => {
                    // Ensure the selected address has a postal code
                    if (address.postalCode) {
                        console.log(
                            "Selected address with postal code:",
                            address
                        );

                        // Extract city and postal code from the selected address
                        const city = address.city || address.county;
                        const postalCode = address.postalCode;

                        // Check if state code is available
                        const stateCode = address.stateCode; // Get the state code
                        let processedState;

                        if (stateCode) {
                            // If state code is available, use it directly
                            processedState = stateCode;
                            console.log("hi");
                        } else {
                            // If state code is not available, check the state name
                            const state = address?.state || ""; // Optional chaining and fallback
                            processedState = state
                                ? state
                                      .split(" ")
                                      .slice(0, 2)
                                      .map((part) =>
                                          part.charAt(0).toUpperCase()
                                      )
                                      .join("")
                                : ""; // Set to empty if no state is available
                        }

                        // Update the input fields with city, state, and postal code
                        if (codeInput) codeInput.value = postalCode; // Set postal code
                        if (cityInput) cityInput.value = city; // Set city
                        if (stateInput) stateInput.value = processedState; // Set processed state

                        console.log(
                            "City, state, and postal code updated successfully!"
                        ); // Success feedback
                    } else {
                        console.log("Address does not have a postal code!");
                        alert("Selected address does not have a postal code.");
                    }
                },
                onError: (error) => {
                    console.error("Error fetching addresses:", error);
                },
            });
        } else {
            console.error(
                `Autocomplete container with ID '${containerId}' not found. Please check your HTML.`
            );
        }
    };

    // Initialize autocomplete for both containers
    initializeAutocomplete(
        "recipientStreet",
        "recipientCity",
        "recipientstateOrProvinceCode",
        "inputToZip" // Ensure postal code is captured for recipient
    );
    initializeAutocomplete(
        "shipperStreet",
        "shipperCity",
        "shipperstateOrProvinceCode",
        "inputFromZip" // Ensure postal code is captured for shipper
    );
});
