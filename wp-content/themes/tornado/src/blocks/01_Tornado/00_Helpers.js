/*global window,wp,Glide, document,breakPoints_990,breakPoints_810,breakPoints_650,breakPoints_490,breakPoints_400, getSiblings ,setInterval, clearInterval,getElements,getElement,getNextSibling,getPrevSibling,setAttributes,getComputedStyle,pageDirection,console*/
/*jslint es6 */

/*====== Gutenberg Components ==========*/
const {__} = wp.i18n;
const {registerBlockType} = wp.blocks;
const {withInstanceId} = wp.compose;
/*===== Editable Components =====*/
if (!wp.editor) {
    const {
        PlainText,
        RichText,
        Editable,
        MediaUpload,
        MediaUploadCheck,
        AlignmentToolbar,
        BlockControls,
        ColorPalette,
        InnerBlocks,
        InspectorControls
    } = wp.blockEditor;
} else {
    const {
        PlainText,
        RichText,
        Editable,
        MediaUpload,
        MediaUploadCheck,
        AlignmentToolbar,
        BlockControls,
        ColorPalette,
        InnerBlocks,
        InspectorControls
    } = wp.editor;
}

/*===== Editor Components =====*/
const {
    Draggable,
    Panel,
    PanelBody,
    PanelRow,
    SelectControl,
    Button,
    DropdownMenu,
    withAPIData,
    TextControl,
    RangeControl,
    FormFileUpload,
    BaseControl
} = wp.components;

/*===== Editor Server Render =====*/
if (!wp.components.serverSideRender) {
    const ServerSideRender = wp.components.serverSideRender;
} else {
    /*===== Editor Server Render =====*/
    const ServerSideRender = wp.serverSideRender;
}
/*===== Editor Elements Components =====*/
const {Component, Fragment} = wp.element;
/*===== Editor Dynamic Data =====*/
const {select, withSelect, dispatch} = wp.data;
/*===== Editor Component Wraper =====*/
const {createHigherOrderComponent} = wp.compose;
