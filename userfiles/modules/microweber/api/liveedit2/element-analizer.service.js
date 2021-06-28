
import {DomService} from './dom';

export class ElementAnalyzerServiceBase {

    constructor(settings) {
        this.settings = settings;
        this.tools = DomService;
    }

    isRow (node) {
        return node.classList.contains(this.settings.rowClass);
    }

    isModule (node) {
        return node.classList.contains(this.settings.moduleClass) && node.dataset.type !== 'layouts';
    }

    isLayout (node) {
        return node.classList.contains(this.settings.moduleClass) && node.dataset.type === 'layouts';
    }

    isInLayout (node) {
        if(!node) {
            return false;
        }
        node = node.parentNode;
        while(node && node !== this.settings.document.body) {
            if(node.classList.contains(this.settings.moduleClass) && node.dataset.type === 'layouts') {
                return true;
            }
            node = node.parentNode
        }
    }

    isElement (node) {
        return node.classList.contains(this.settings.elementClass);
    }

    isEmptyElement (node) {
        return node.classList.contains(this.settings.emptyElementClass);
    }

    isEdit (node) {
        return node.classList.contains(this.settings.editClass);
    }

    isInEdit (node) {
        var order = [
            this.settings.editClass,
            this.settings.moduleClass,
        ];
        return this.tools.parentsOrCurrentOrderMatchOrOnlyFirst(node.parentNode, order);
    }

    isEditOrInEdit (node) {
        return this.isEdit(node) || this.isInEdit(node);
    }

    isPlainText (node) {
        return node.classList.contains(this.settings.plainElementClass);
    }

    getType(node) {
        if(this.isEdit(node)) {
            return 'edit';
        } else if(this.isElement(node)) {
            return 'element';
        } else if(this.isModule(node)) {
            return 'module';
        }  else if(this.isLayout(node)) {
            return 'layout';
        }
    }
}
