<?php


namespace app\models;


class Roadmap extends PropertyContainer
{

    public function __construct($id)
    {
        $segment = Segment::findOne($id);
        $confirm_segment = Segment::find()->where(['id' => $id, 'exist_confirm' =>  1])->one();

        $last_gps = GenerationProblem::find()->where(['interview_id' => $segment->interview->id])->orderBy(['created_at' => SORT_DESC])->one();
        $first_confirm_gps = GenerationProblem::find()->where(['interview_id' => $segment->interview->id, 'exist_confirm' => 1])->andWhere(['not', ['time_confirm' => null]])->orderBy(['time_confirm' => SORT_ASC])->one();

        $last_gcp = Gcp::find()->where(['segment_id' => $segment->id])->orderBy(['created_at' => SORT_DESC])->one();
        $first_confirm_gcp = Gcp::find()->where(['segment_id' => $segment->id, 'exist_confirm' => 1])->andWhere(['not', ['time_confirm' => null]])->orderBy(['time_confirm' => SORT_ASC])->one();

        $last_mvp = Mvp::find()->where(['segment_id' => $segment->id])->orderBy(['created_at' => SORT_DESC])->one();
        $first_confirm_mvp = Mvp::find()->where(['segment_id' => $segment->id, 'exist_confirm' => 1])->andWhere(['not', ['time_confirm' => null]])->orderBy(['time_confirm' => SORT_ASC])->one();

        $this->addProperty('created_at', $segment->created_at);
        $this->addProperty('plan_segment_confirm', ($segment->created_at + 3600*24*30));
        $this->addProperty('plan_gps', ($segment->created_at + 3600*24*60));
        $this->addProperty('plan_gps_confirm', ($segment->created_at + 3600*24*90));
        $this->addProperty('plan_gcp', ($segment->created_at + 3600*24*120));
        $this->addProperty('plan_gcp_confirm', ($segment->created_at + 3600*24*150));
        $this->addProperty('plan_mvp', ($segment->created_at + 3600*24*180));
        $this->addProperty('plan_mvp_confirm', ($segment->created_at + 3600*24*210));

        $this->addProperty('fact_segment_confirm', $confirm_segment->time_confirm);
        $this->addProperty('fact_gps', $last_gps->created_at);
        $this->addProperty('fact_gps_confirm', $first_confirm_gps->time_confirm);
        $this->addProperty('fact_gcp', $last_gcp->created_at);
        $this->addProperty('fact_gcp_confirm', $first_confirm_gcp->time_confirm);
        $this->addProperty('fact_mvp', $last_mvp->created_at);
        $this->addProperty('fact_mvp_confirm', $first_confirm_mvp->time_confirm);

        $this->addProperty('id_confirm_segment', $segment->interview->id);
        $this->addProperty('id_page_last_problem', $last_gps->interview_id);
        $this->addProperty('id_confirm_problem', $first_confirm_gps->confirm->id);
        $this->addProperty('id_page_last_gcp', $last_gcp->confirm_problem_id);
        $this->addProperty('id_confirm_gcp', $first_confirm_gcp->confirm->id);
        $this->addProperty('id_page_last_mvp', $last_mvp->confirm_gcp_id);
        $this->addProperty('id_confirm_mvp', $first_confirm_mvp->confirm->id);
        $this->addProperty('segment_name', $segment->name);

        return $this;
    }

}