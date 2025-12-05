import Phaser from 'phaser';

export interface DialogNode {
    id: number;
    speaker: string;
    text: string;
    portrait: string;
    next: number | null;
}

export interface DialogData {
    [key: string]: DialogNode[];
}

export class DialogManager extends Phaser.Events.EventEmitter {
    private scene: Phaser.Scene;
    private dialogs: DialogData = {};
    private currentDialogSequence: DialogNode[] = [];
    private currentNodeIndex: number = -1;

    constructor(scene: Phaser.Scene) {
        super();
        this.scene = scene;
    }

    public loadDialogs(key: string) {
        const data = this.scene.cache.json.get(key);
        if (data) {
            this.dialogs = data;
        } else {
            console.error(`Dialog data not found for key: ${key}`);
        }
    }

    public startDialog(sequenceKey: string) {
        if (this.dialogs[sequenceKey]) {
            this.currentDialogSequence = this.dialogs[sequenceKey];
            this.currentNodeIndex = 0;
            this.emitDialogUpdate();
            this.emit('dialog-start');
        } else {
            console.error(`Dialog sequence not found: ${sequenceKey}`);
        }
    }

    public nextDialog() {
        if (this.currentNodeIndex >= 0 && this.currentNodeIndex < this.currentDialogSequence.length) {
            const currentNode = this.currentDialogSequence[this.currentNodeIndex];
            if (currentNode.next !== null) {
                // Find the index of the next node based on ID
                const nextNodeIndex = this.currentDialogSequence.findIndex(node => node.id === currentNode.next);
                if (nextNodeIndex !== -1) {
                    this.currentNodeIndex = nextNodeIndex;
                    this.emitDialogUpdate();
                } else {
                    this.endDialog();
                }
            } else {
                this.endDialog();
            }
        }
    }

    private emitDialogUpdate() {
        const node = this.currentDialogSequence[this.currentNodeIndex];
        this.emit('dialog-update', node);
    }

    private endDialog() {
        this.currentDialogSequence = [];
        this.currentNodeIndex = -1;
        this.emit('dialog-end');
    }
}
