(function(window, document, $, undefined) {
    "use strict";

    var RAELCrossSiteCopy = {
        //Initializing properties and methods
        init: function(e) {
            RAELCrossSiteCopy.globalVars();
            RAELCrossSiteCopy.loadraelCrossSiteCopyLocalStorage();
            RAELCrossSiteCopy.loadContextMenuGroupsHooks();
        },

        setElementId: function(elements) {
            return (
                elements.forEach(function(item) {
                    (item.id = elementorCommon.helpers.getUniqueId()),
                    0 < item.elements.length &&
                        RAELCrossSiteCopy.setElementId(item.elements);
                }),
                elements
            );
        },
        globalVars: function(e) {
            window.lc_ajax_url = rael_cs_copy.ajax_url;
            window.lc_ajax_nonce = rael_cs_copy.nonce;
            window.lc_key = rael_cs_copy.front_key;
        },
        loadraelCrossSiteCopyLocalStorage: function() {
            raelCrossSiteCopyLocalStorage.init({
				iframeUrl: "https://api.cyberchimps.com/reaLCP/",
                initCallback: function() {
                    // if need any callback
                },
            });
        },
        loadContextMenuGroupsHooks: function() {
            elementor.hooks.addFilter(
                "elements/section/contextMenuGroups",
                function(groups, element) {
                    return RAELCrossSiteCopy.prepareMenuItem(groups, element);
                }
            );

            elementor.hooks.addFilter(
                "elements/widget/contextMenuGroups",
                function(groups, element) {
                    return RAELCrossSiteCopy.prepareMenuItem(groups, element);
                }
            );

            elementor.hooks.addFilter(
                "elements/column/contextMenuGroups",
                function(groups, element) {
                    return RAELCrossSiteCopy.prepareMenuItem(groups, element);
                }
            );

            elementor.hooks.addFilter(
                "elements/container/contextMenuGroups",
                function(groups, element) {
                    return RAELCrossSiteCopy.prepareMenuItem(groups, element);
                }
            );
        },
        prepareMenuItem: function(groups, element) {
            var index = _.findIndex(groups, function(element) {
                return "clipboard" === element.name;
            });
            groups.splice(index + 1, 0, {
                name: "rael-cs-copy-paste",
                actions: [{
                        name: "rael-cs-copy",
                        title: "RAE Copy",
                        icon: "eicon-copy",
                        callback: function() {
                            RAELCrossSiteCopy.raelCSCopy(element);
                        },
                    },
                    {
                        name: "rael-cs-paste",
                        title: "RAE Paste",
                        icon: "eicon-clone",
                        callback: function() {
                            RAELCrossSiteCopy.raelCSPaste(element);
                        },
                    },
                ],
            });

            return groups;
        },
        raelCSCopy: function(e) {
            var data = {
                elType: e.model.attributes.elType,
                eletype: e.model.get("widgetType"),
                modelJson: e.model.toJSON(),
            };

            const params = {};
            params[data.elType] = data;

            raelCrossSiteCopyLocalStorage.setItem(
                "front_copy_data",
                JSON.stringify(params)
            );
        },

        raelCSPaste: function(selectedContainer) {
            raelCrossSiteCopyLocalStorage.getItem(
                "front_copy_data",
                function(frontContent) {
                    var containerElType = selectedContainer.model.get("elType");
                    var frontData = JSON.parse(frontContent.value);
                    console.error(frontContent);
                    var frontDataKey = Object.keys(frontData)[0];

                    var elementData = frontData[frontDataKey];
                    var elementType, elementModel, encodedElementModel;

                    if (typeof elementData.modelJson == "undefined") {
                        elementType = elementData.elType;
                        elementModel = elementData;
                    } else {
                        elementType = elementData.modelJson.elType;
                        elementModel = elementData.modelJson;
                    }

                    encodedElementModel = JSON.stringify(elementModel);

                    const hasImageFiles = /\.(jpg|png|jpeg|gif|svg)/gi.test(
                        encodedElementModel
                    );
                    const importModel = {
                        elType: elementType,
                        settings: elementModel.settings,
                    };

                    var importContainer = null;
                    var importOption = {
                        index: 0
                    };

                    if (elementType == "section") {
                        importModel.elements = RAELCrossSiteCopy.setElementId(
                            elementModel.elements
                        );
                        importContainer = elementor.getPreviewContainer();
                    } else if (elementType == "column") {
                        importModel.elements = RAELCrossSiteCopy.setElementId(
                            elementModel.elements
                        );
                        if ("section" === containerElType) {
                            importContainer = selectedContainer.getContainer();
                        }
                        else if ("column" === containerElType) {
                            importContainer = selectedContainer.getContainer().parent;
                            importOption.index = selectedContainer.getOption("_index") + 1;
                        } else if ("widget" === containerElType) {
                            importContainer = selectedContainer.getContainer().parent.parent;
                            importOption.index =
                                selectedContainer
                                .getContainer()
                                .parent.view.getOption("_index") + 1;
                        } else {
                            console.log("not match RAEL CS Copy ElType");
                            return;
                        }
                    } else if (elementType == "widget") {
                        (importModel.widgetType = elementData.eletype),
                        (importContainer = selectedContainer.getContainer());

                        if ("section" === containerElType) {
                            importContainer = selectedContainer.children
                                .findByIndex(0)
                                .getContainer();
                        } else if ("column" === containerElType) {
                            importContainer = importContainer =
                                selectedContainer.getContainer();
                        } else if ("widget" === containerElType) {
                            importContainer = selectedContainer.getContainer().parent;
                            importOption.index = selectedContainer.getOption("_index") + 1;
                        } else if ("container" === containerElType) {
                            importContainer = selectedContainer.getContainer();
                        } else {
                            console.log("not match RAEL CS Copy ElType");
                            return;
                        }
                    } else if (elementType === "container") {
                        importModel.elements = RAELCrossSiteCopy.setElementId(
                            elementModel.elements
                        );
                        importContainer = elementor.getPreviewContainer();
                    }

                    var importedContainer = $e.run("document/elements/create", {
                        model: importModel,
                        container: importContainer,
                        options: importOption,
                    });

                    if (hasImageFiles) {
                        $.ajax({
                            url: lc_ajax_url,
                            method: "POST",
                            data: {
                                action: "rael_elementor_import_rael_cs_copy_assets_files",
                                data: encodedElementModel,
                                security: lc_ajax_nonce,
                            },
                            beforeSend: function() {
                                importedContainer.view.$el.append(
                                    '<div id="rael-cs-copy-importing-images-loader">Importing Images..</div>'
                                );
                            },
                        }).done(function(response) {
                            if (response.success) {
                                const data = response.data[0];
                                importModel.elType = data.elType;
                                importModel.settings = data.settings;

                                if ("widget" === data.elType) {
                                    importModel.widgetType = data.widgetType;
                                } else {
                                    importModel.elements = data.elements;
                                }

                                setTimeout(function() {
                                    $e.run("document/elements/delete", {
                                        container: importedContainer,
                                    });
                                    $e.run("document/elements/create", {
                                        model: importModel,
                                        container: importContainer,
                                        options: importOption,
                                    });
                                }, 800);

                                $("#rael-cs-copy-importing-images-loader").remove();
                            }
                        });
                    }
                }
            );
        },
    };
    RAELCrossSiteCopy.init();
})(window, document, jQuery, raelCrossSiteCopyLocalStorage);
