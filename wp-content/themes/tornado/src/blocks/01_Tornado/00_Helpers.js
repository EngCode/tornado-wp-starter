/*global window,wp,Glide, document,breakPoints_990,breakPoints_810,breakPoints_650,breakPoints_490,breakPoints_400, getSiblings ,setInterval, clearInterval,getElements,getElement,getNextSibling,getPrevSibling,setAttributes,getComputedStyle,pageDirection,console*/
/*jslint es6 */

/*====== Gutenberg Components ==========*/
const {__} = wp.i18n;
const {registerBlockType} = wp.blocks;
const {withInstanceId} = wp.compose;
/*===== Editable Components =====*/
const {
    PlainText,
    InspectorControls,
    RichText,
    Editable,
    MediaUpload,
    MediaUploadCheck,
    AlignmentToolbar,
    BlockControls,
    ColorPalette
} = wp.editor;

const {InnerBlocks} = wp.blockEditor;
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
    ServerSideRender,
    RangeControl,
    FormFileUpload,
    BaseControl
} = wp.components;
/*===== Editor Elements Components =====*/
const {Component, Fragment} = wp.element;
/*===== Editor Dynamic Data =====*/
const {select, withSelect, dispatch} = wp.data;
/*===== Editor Component Wraper =====*/
const {createHigherOrderComponent} = wp.compose;
